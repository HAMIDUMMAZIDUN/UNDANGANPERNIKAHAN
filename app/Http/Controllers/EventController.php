<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventController extends Controller
{
    /**
     * Menampilkan formulir untuk membuat event baru.
     * Method ini wajib ada jika Anda punya rute ke 'events.create'.
     */
    public function create(): View
    {
        // Mengembalikan view yang berisi form HTML
        return view('user.events.create');
    }

    /**
     * Menyimpan event baru yang dikirim dari form `create`.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi semua input dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            // Tambahkan validasi lain jika ada
        ]);

        try {
            // 2. Tambahkan user_id dan uuid ke data yang akan disimpan
            $validatedData['slug'] = Str::slug($validatedData['name'], '-');
            $validatedData['user_id'] = Auth::id();
            $validatedData['uuid'] = Str::uuid(); // Generate UUID unik untuk event

            // 3. Buat event baru di database
            $event = Event::create($validatedData);

            // 4. Redirect ke halaman dashboard (atau halaman lain) dengan pesan sukses
            // PERBAIKAN: Redirect ke rute yang ada, misalnya dashboard.
            return redirect()->route('dashboard.index')->with('success', 'Event baru berhasil dibuat!');

        } catch (\Exception $e) {
            // 5. Jika terjadi error, catat log dan kembalikan ke halaman sebelumnya
            Log::error('Gagal menyimpan event: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    /**
     * Menampilkan detail sebuah event.
     * (Opsional, tapi baik untuk dimiliki jika ada rute 'events.show')
     */
    public function show(Event $event): View
    {
        // Pastikan hanya pemilik yang bisa melihat
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.events.show', compact('event'));
    }

    /**
     * Menampilkan form untuk mengedit event.
     */
    public function edit(Event $event): View
    {
        // Pastikan event ini milik user yang sedang login
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('user.events.edit', compact('event'));
    }

    /**
     * Memperbarui data event di database.
     */
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
        
        // Menggunakan update() lebih efisien
        $event->update([
            'name' => $validatedData['name'],
            'date' => $validatedData['date'],
        ]);

        if ($request->hasFile('photo_url')) {
            // Hapus foto lama jika ada
            if ($event->photo_url && File::exists(public_path('storage/' . $event->photo_url))) {
                File::delete(public_path('storage/' . $event->photo_url));
            }

            // Simpan foto baru ke public/storage/foto-event
            $path = $request->file('photo_url')->store('foto-event', 'public');
            $event->photo_url = $path;
            $event->save();
        }

        return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
    }
}
