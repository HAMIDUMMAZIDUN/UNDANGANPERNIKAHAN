<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DesignController extends Controller
{
    /**
     * Menampilkan halaman editor desain utama.
     */
    public function index()
    {
        return view('admin.design.index');
    }

    /**
     * Menampilkan halaman untuk membuat desain baru.
     */
    public function create()
    {
        return view('admin.design.index');
    }
    
    /**
     * Menampilkan semua desain yang tersimpan dari database dan file template.
     */
    public function showSavedDesigns(Request $request)
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

                    $fileTemplate = new class {
                        public $id;
                        public $name;
                        public $is_file_template = true;
                        public $created_at;
                        public $updated_at;
                        public $thumbnail;

                        public function getKey()
                        {
                            return $this->id;
                        }
                    };

                    $fileTemplate->id = $fileName;
                    $fileTemplate->name = Str::of($fileName)->replace('-', ' ')->title();
                    $fileTemplate->created_at = \Carbon\Carbon::createFromTimestamp($file->getMTime());
                    $fileTemplate->updated_at = \Carbon\Carbon::createFromTimestamp($file->getMTime());
                    $fileTemplate->thumbnail = 'https://placehold.co/600x400/f97316/white?text=' . Str::of($fileName)->replace('-', ' ')->title();

                    $fileTemplates->push($fileTemplate);
                }
            }
        }

        $allDesigns = $dbDesigns->merge($fileTemplates)->sortByDesc('created_at');

        $perPage = 9;
        $currentPage = $request->get('page', 1);
        $currentPageItems = $allDesigns->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $designs = new LengthAwarePaginator(
            $currentPageItems,
            $allDesigns->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.design.saved', compact('designs'));
    }

    /**
     * Menyimpan desain baru ke database.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|json'
        ]);

        $design = Design::create($validated);
        
        if (method_exists($design, 'addMediaFromUrl')) {
             $design->addMediaFromUrl('https://placehold.co/600x400/cccccc/ffffff?text=' . urlencode($validated['name']))
                    ->toMediaCollection('thumbnail');
        }

        return response()->json([
            'success' => true,
            'message' => 'Desain berhasil disimpan!',
            'redirect_url' => route('admin.design.saved_designs')
        ]);
    }

    /**
     * Menampilkan halaman editor untuk desain yang sudah ada.
     */
    public function edit(Design $design)
    {
        return view('admin.design.index', compact('design'));
    }

    /**
     * Memperbarui desain yang sudah ada di database.
     */
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

    /**
     * Menghapus desain dari database.
     */
    public function destroy(Design $design)
    {
        $design->delete();
        return redirect()->route('admin.design.saved_designs')->with('success', 'Desain berhasil dihapus.');
    }

    /**
     * Menampilkan preview untuk desain yang sudah tersimpan di database.
     */
    public function showPreview(Design $design)
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

        // Render the design structure into HTML
        $renderedContent = $this->renderDesign($design->structure, $dummyEvent);

        return view('admin.design.preview', compact('design', 'dummyEvent', 'renderedContent'));
    }

    /**
     * Membuat live preview dari data yang dikirim oleh editor.
     */
    public function livePreview(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'structure' => 'required|json',
        ]);

        $design = new \stdClass();
        $design->id = 0; // Tambahkan id dummy
        $design->name = $request->input('name');
        
        // --- PERBAIKAN DI SINI ---
        // Ambil 'structure' dari request dan decode
        $structure = json_decode($request->input('structure'), true);

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
        ];
        
        // Gunakan $structure yang sudah di-decode untuk merender konten
        $renderedContent = $this->renderDesign($structure, $dummyEvent);

        return view('admin.design.preview', compact('design', 'dummyEvent', 'renderedContent'))->render();
    }
    
    /**
     * Menangani unggahan gambar dari editor.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('designs', 'public');
            $url = Storage::url($path);

            return response()->json([ 'success' => true, 'url' => asset($url) ]);
        }

        return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 400);
    }

    /**
     * Mengekspor struktur desain sebagai file JSON.
     */
    public function export(Design $design)
    {
        $jsonContent = json_encode($design->structure, JSON_PRETTY_PRINT);
        $fileName = Str::slug($design->name) . '.json';

        return response()->streamDownload(function () use ($jsonContent) {
            echo $jsonContent;
        }, $fileName, [ 'Content-Type' => 'application/json' ]);
    }

    /**
     * Mengimpor desain dari file JSON.
     */
    public function import(Request $request)
    {
        $request->validate([ 'design_file' => 'required|file|mimes:json' ]);

        $file = $request->file('design_file');
        $content = $file->get();

        $structure = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['design_file' => 'File JSON tidak valid.']);
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $name = Str::of($fileName)->replace(['-', '_'], ' ')->title();

        Design::create([ 'name' => $name, 'structure' => $structure ]);

        return redirect()->route('admin.design.saved_designs')->with('success', "Desain '{$name}' berhasil diimpor.");
    }

    /**
     * Helper method to render a design's structure into an HTML string.
     */
    private function renderDesign($structure, $event)
    {
        $html = '';
        if (!is_array($structure)) {
            return '';
        }

        foreach ($structure as $element) {
            $settings = (object)($element['settings'] ?? []);
            $type = $element['type'] ?? 'unknown';

            switch ($type) {
                case 'cover':
                    $bgImage = !empty($settings->backgroundImage) ? $settings->backgroundImage : $event->cover_photo_url;
                    $html .= "<div class=\"relative min-h-[80vh] flex items-center justify-center bg-cover bg-center\" style=\"background-image: url('{$bgImage}')\">";
                    $html .= "<div class=\"absolute inset-0\" style=\"background-color: {$settings->overlayColor}\"></div>";
                    $html .= "<div class=\"relative text-center p-4\" style=\"color: {$settings->textColor}\">";
                    $html .= "<p class=\"text-sm tracking-widest uppercase mb-4\">" . htmlspecialchars($settings->title) . "</p>";
                    $html .= "<h1 class=\"font-serif text-5xl md:text-7xl mb-6\">" . htmlspecialchars($settings->brideName) . "<br>&<br>" . htmlspecialchars($settings->groomName) . "</h1>";
                    $html .= "<p class=\"text-lg\">" . htmlspecialchars($settings->date) . "</p>";
                    $html .= "</div></div>";
                    break;
                case 'couple':
                    $layoutClass = ($settings->layout ?? 'horizontal') === 'horizontal' ? 'md:flex-row' : 'md:flex-col';
                    $bridePhoto = !empty($settings->bridePhoto) ? $settings->bridePhoto : $event->couple_photo_url;
                    $groomPhoto = !empty($settings->groomPhoto) ? $settings->groomPhoto : 'https://placehold.co/192x192/e2e8f0/cccccc?text=Groom';
                    $html .= "<div class=\"flex flex-col {$layoutClass} justify-center items-center py-8\" style=\"gap: ".($settings->spacing ?? 30)."px;\">";
                    $html .= "<div class=\"text-center\"><img src=\"{$bridePhoto}\" alt=\"Bride\" class=\"w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md\"><h3 class=\"font-serif text-2xl mb-2\">" . htmlspecialchars($settings->brideName) . "</h3><p class=\"text-gray-600\">" . htmlspecialchars($settings->brideParents) . "</p></div>";
                    $html .= "<div class=\"text-6xl font-serif my-4 md:my-0 text-gray-300\">&</div>";
                    $html .= "<div class=\"text-center\"><img src=\"{$groomPhoto}\" alt=\"Groom\" class=\"w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md\"><h3 class=\"font-serif text-2xl mb-2\">" . htmlspecialchars($settings->groomName) . "</h3><p class=\"text-gray-600\">" . htmlspecialchars($settings->groomParents) . "</p></div>";
                    $html .= "</div>";
                    break;
                case 'heading':
                    $html .= "<h".($settings->level ?? 2)." style=\"color: {$settings->color}; font-family: '{$settings->fontFamily}', serif; font-size: {$settings->fontSize}px; text-align: {$settings->alignment}; margin: {$settings->margin}px 0;\">";
                    $html .= htmlspecialchars($settings->text);
                    $html .= "</h".($settings->level ?? 2).">";
                    break;
                case 'text':
                    $html .= "<p style=\"color: {$settings->color}; font-family: '{$settings->fontFamily}', sans-serif; font-size: {$settings->fontSize}px; line-height: {$settings->lineHeight}; text-align: {$settings->alignment}; white-space: pre-wrap;\">";
                    $html .= nl2br(htmlspecialchars($settings->text));
                    $html .= "</p>";
                    break;
                default:
                    $html .= "<div class=\"p-4 border border-dashed border-gray-300 rounded\">Unknown element type: " . htmlspecialchars($type) . "</div>";
                    break;
            }
        }
        return $html;
    }
}

