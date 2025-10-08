<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class DesignController extends Controller
{
    protected const CACHE_TTL = 3600; // 1 hour
    protected const PER_PAGE = 9;
    protected const EXCLUDED_TEMPLATES = ['public', 'show'];

    public function index()
    {
        return view('admin.design.index');
    }

    public function create()
    {
        return view('admin.design.index');
    }
    
    public function showSavedDesigns(Request $request)
    {
        try {
            $cacheKey = 'designs_page_' . $request->get('page', 1);
            
            $paginator = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request) {
                $dbDesigns = Design::latest()->get();
                $fileTemplates = $this->getFileTemplates();
                
                $allDesigns = $dbDesigns->merge($fileTemplates)->sortByDesc('created_at');
                
                return $this->paginateCollection($allDesigns, $request);
            });

            return view('admin.design.saved', compact('paginator'));
        } catch (Exception $e) {
            Log::error('Error fetching designs: ' . $e->getMessage());
            return view('admin.design.saved', [
                'paginator' => new LengthAwarePaginator([], 0, self::PER_PAGE, 1)
            ])->withError('Unable to load designs. Please try again.');
        }
    }

    protected function paginateCollection($collection, Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = self::PER_PAGE;
        
        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    protected function getFileTemplates()
    {
        $fileTemplates = collect();
        $templatePath = resource_path('views/undangan/templates');

        if (!File::isDirectory($templatePath)) {
            return $fileTemplates;
        }

        return collect(File::files($templatePath))
            ->filter(fn($file) => $file->getExtension() === 'php')
            ->reject(fn($file) => in_array(
                pathinfo($file->getFilename(), PATHINFO_FILENAME), 
                self::EXCLUDED_TEMPLATES
            ))
            ->map(function($file) {
                return $this->createFileTemplateObject($file);
            });
    }

    protected function createFileTemplateObject($file)
    {
        $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
        
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
        $fileTemplate->thumbnail = $this->generateThumbnailUrl($fileName);

        return $fileTemplate;
    }

    protected function generateThumbnailUrl($fileName)
    {
        return "https://placehold.co/600x400/f97316/white?text=" . urlencode(Str::of($fileName)->replace('-', ' ')->title());
    }

    protected function paginateDesigns($designs, Request $request)
    {
        $currentPage = $request->get('page', 1);
        $currentPageItems = $designs->slice(($currentPage - 1) * self::PER_PAGE, self::PER_PAGE)->values();
        
        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $designs->count(),
            self::PER_PAGE,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.design.saved', [
            'paginator' => $paginator
        ]);
    }

    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'structure' => 'required|json'
            ]);

            $design = Design::create($validated);
            
            if (method_exists($design, 'addMediaFromUrl')) {
                $design->addMediaFromUrl($this->generateThumbnailUrl($validated['name']))
                       ->toMediaCollection('thumbnail');
            }

            Cache::tags(['designs'])->flush();

            return response()->json([
                'success' => true,
                'message' => 'Desain berhasil disimpan!',
                'redirect_url' => route('admin.design.saved_designs')
            ]);
        } catch (Exception $e) {
            Log::error('Error saving design: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan desain. Silakan coba lagi.'
            ], 500);
        }
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

    public function showPreview(Design $design)
    {
        return view('admin.design.preview', [
            'design' => $design,
            'renderedContent' => 'Konten dirender di sini'
        ]);
    }

    public function livePreview(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'structure' => 'required|json',
        ]);

        $design = (object)[
            'id' => null,
            'name' => $request->input('name', 'Live Preview'),
        ];

        $structure = json_decode($request->input('structure'), true);

        $renderedContent = '';
        if (is_array($structure)) {
            foreach ($structure as $component) {
                $renderedContent .= $this->renderComponentToHtml($component);
            }
        }

        return view('admin.design.preview', compact('design', 'renderedContent'));
    }

    private function renderComponentToHtml(array $item): string
    {
        try {
            $data = $item['data'] ?? [];
            $styles = $item['styles'] ?? [];
            $type = $item['type'] ?? 'unknown';
            
            $styleString = $this->buildStyleString($styles);
            $content = $this->renderComponentContent($type, $data);
            
            return $this->wrapComponent($content, $styleString, $styles['padding'] ?? 48);
        } catch (Exception $e) {
            Log::error("Error rendering component: {$e->getMessage()}");
            return '<div class="p-4 text-red-500">Error rendering component</div>';
        }
    }

    protected function buildStyleString(array $styles): string
    {
        $baseStyles = [
            'backgroundColor' => '#FFFFFF',
            'color' => '#334155',
            'textAlign' => 'center',
            'backgroundSize' => 'cover',
            'backgroundPosition' => 'center'
        ];

        $styleString = '';
        foreach ($baseStyles as $property => $defaultValue) {
            $value = $styles[$property] ?? $defaultValue;
            $styleString .= "{$property}: " . htmlspecialchars($value) . "; ";
        }

        if (!empty($styles['backgroundImage'])) {
            $imageUrl = htmlspecialchars($styles['backgroundImage']);
            $styleString .= "background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('{$imageUrl}'); ";
            $styleString .= "color: #FFFFFF; ";
        }

        return $styleString;
    }
    
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

    public function export(Design $design)
    {
        $jsonContent = json_encode($design->structure, JSON_PRETTY_PRINT);
        $fileName = Str::slug($design->name) . '.json';
        return response()->streamDownload(function () use ($jsonContent) {
            echo $jsonContent;
        }, $fileName, [ 'Content-Type' => 'application/json' ]);
    }

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
}