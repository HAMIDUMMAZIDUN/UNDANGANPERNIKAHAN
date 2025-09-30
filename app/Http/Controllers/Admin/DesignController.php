<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    public function index()
    {
        return view('admin.design.index');
    }
    
    public function showSavedDesigns()
    {
        $dbDesigns = Design::latest()->get();

        $fileTemplates = collect();
        $templatePath = resource_path('views/undangan/templates');

        if (File::isDirectory($templatePath)) {
            $files = File::files($templatePath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    
                    if (in_array($fileName, ['public', 'show'])) {
                        continue;
                    }

                    $fileTemplates->push((object)[
                        'id' => $fileName,
                        'name' => Str::of($fileName)->replace('-', ' ')->title(),
                        'is_file_template' => true,
                        'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                        'preview_image' => 'https://placehold.co/600x400/f97316/white?text=' . Str::of($fileName)->replace('-', ' ')->title(),
                    ]);
                }
            }
        }

        $designs = $dbDesigns->toBase()->merge($fileTemplates)->sortByDesc('created_at');

        return view('admin.design.saved', compact('designs'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|json'
        ]);

        Design::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil disimpan!',
            'redirect_url' => route('admin.design.saved_designs')
        ]);
    }

    public function edit(Design $design)
    {
        return view('admin.design.index', compact('design'));
    }

    public function update(Request $request, Design $design)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|json'
        ]);

        $design->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil diperbarui!',
            'redirect_url' => route('admin.design.saved_designs')
        ]);
    }

    public function destroy(Design $design)
    {
        $design->delete();
        return redirect()->route('admin.design.saved_designs')->with('success', 'Desain berhasil dihapus.');
    }

    public function preview(Design $design)
    {
        $formattedDate = now()->isoFormat('dddd, D MMMM YYYY');
        $dummyEvent = (object) [
            'groom_name' => 'Putra', 'groom_parents' => 'Bapak Budi & Ibu Wati', 'bride_name' => 'Putri', 'bride_parents' => 'Bapak Santoso & Ibu Lestari', 'date_formatted' => $formattedDate,
            'cover_photo_url' => 'https://images.unsplash.com/photo-1597861405922-26b21c43c4f9?q=85&fm=jpg&w=1200',
            'couple_photo_url' => 'https://images.unsplash.com/photo-1529602264082-036993544063?q=85&fm=jpg&w=1200',
            'video_placeholder_url' => 'https://images.unsplash.com/photo-1515934323957-66a7a092a452?q=85&fm=jpg&w=1200',
            'akad_time' => '08:00 - 10:00 WIB',
            'resepsi_time' => '11:00 - 14:00 WIB',
            'akad_location' => 'Masjid Istiqlal, Jakarta',
            'resepsi_location' => 'Gedung Balai Kartini, Jakarta',
            'gallery_photos' => [['url' => 'https://images.unsplash.com/photo-1523438943922-386d36e439fe?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1606992257321-2527ac312a02?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1532712938310-34cb39825785?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1546193435-99805a99859f?q=85&fm=jpg&w=800'],]
        ];

        return view('admin.design.preview', compact('design', 'dummyEvent'));
    }

    public function export(Design $design)
    {
        $jsonContent = json_encode($design->structure, JSON_PRETTY_PRINT);
        $fileName = Str::slug($design->name) . '.json';

        return response()->streamDownload(function () use ($jsonContent) {
            echo $jsonContent;
        }, $fileName, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'design_file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('design_file');
        $content = $file->get();

        $structure = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['design_file' => 'File JSON tidak valid.']);
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = Str::of($fileName)->replace(['-', '_'], ' ')->title();

        Design::create([
            'name' => $name,
            'structure' => $structure,
        ]);

        return redirect()->route('admin.design.saved_designs')->with('success', "Desain '{$name}' berhasil diimpor.");
    }
}

