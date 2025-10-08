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
        $designs = Design::latest()->paginate(12);
        return view('admin.design.list', compact('designs'));
    }

    public function create()
    {
        return view('admin.design.editor');
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


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'structure' => 'required|array',
            'preview_image' => 'nullable|string',
            'category' => 'required|string|in:basic,premium,exclusive'
        ]);

        try {
            $design = Design::create($validated);
            
            return response()->json([
                'message' => 'Desain berhasil disimpan',
                'design' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan desain'
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
            'structure' => 'required|array',
            'preview_image' => 'nullable|string',
            'category' => 'required|string|in:basic,premium,exclusive'
        ]);

        try {
            $design->update($validated);
            
            return response()->json([
                'message' => 'Desain berhasil diperbarui',
                'design' => $design
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui desain'
            ], 500);
        }
    }

    public function destroy(Design $design)
    {
        try {
            $design->delete();
            return response()->json([
                'message' => 'Desain berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus desain'
            ], 500);
        }
    }

    public function preview(Design $design)
    {
        // Implementation for preview
        return view('admin.design.preview', compact('design'));
    }

    public function livePreview(Request $request)
    {
        $validated = $request->validate([
            'structure' => 'required|array',
            'event_data' => 'required|array'
        ]);

        try {
            // Render the preview with the current structure and event data
            return view('preview.invitation', [
                'structure' => $validated['structure'],
                'event' => (object) $validated['event_data']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate preview: ' . $e->getMessage()
            ], 500);
        }
    }
}