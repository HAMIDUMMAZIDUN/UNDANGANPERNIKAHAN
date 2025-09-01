<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse; // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // =================================================================
    // METHOD UNTUK MANAJEMEN DATA EVENT (SUDAH ADA SEBELUMNYA)
    // =================================================================

    public function create(): View
    {
        return view('user.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        try {
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
            $validatedData['user_id'] = Auth::id();
            $validatedData['uuid'] = Str::uuid();
            $event = Event::create($validatedData);
            return redirect()->route('dashboard.index')->with('success', 'Event baru berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan event: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function show(Event $event): View
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);
        
        $event->update([
            'name' => $validatedData['name'],
            'date' => $validatedData['date'],
        ]);

        if ($request->hasFile('photo_url')) {
            if ($event->photo_url && File::exists(public_path('storage/' . $event->photo_url))) {
                File::delete(public_path('storage/' . $event->photo_url));
            }
            $path = $request->file('photo_url')->store('foto-event', 'public');
            $event->photo_url = $path;
            $event->save();
        }

        return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
    }


    // =================================================================
    // METHOD BARU UNTUK PAGE BUILDER DRAG & DROP
    // =================================================================

    /**
     * Menampilkan halaman editor drag-and-drop untuk mendesain tampilan event.
     */
    public function design(Event $event): View
    {
        // Otorisasi: Pastikan event ini milik user yang sedang login
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // Mengembalikan view khusus untuk editor. Ganti nama jika perlu.
        return view('user.events.design', compact('event'));
    }

    /**
     * Menyimpan konten JSON dari editor drag-and-drop.
     * Ini adalah endpoint API yang dipanggil oleh JavaScript.
     */
    public function saveDesign(Request $request, Event $event): JsonResponse
    {
        // Otorisasi
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi bahwa 'content' ada dan merupakan sebuah array
        $validated = $request->validate([
            'content' => 'required|array'
        ]);

        // Simpan data JSON ke kolom 'content'
        $event->content = $validated['content'];
        $event->save();

        // Kembalikan response JSON sebagai konfirmasi
        return response()->json(['message' => 'Desain berhasil disimpan.']);
    }
    
    /**
     * Menampilkan halaman event publik yang bisa dilihat oleh tamu.
     */
    public function publicShow(string $slug): View
    {
        // Cari event berdasarkan slug, jika tidak ada akan menampilkan error 404
        $event = Event::where('slug', $slug)->firstOrFail();

        // Mengembalikan view publik
        return view('public.event-show', compact('event'));
    }
}