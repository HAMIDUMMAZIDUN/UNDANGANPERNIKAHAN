<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman settings dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request): View
    {
        $query = Event::withCount('guests')
                      // [DIUBAH] Mengambil data tamu pertama untuk setiap event
                      ->with(['guests' => function ($query) {
                          $query->limit(1);
                      }])
                      ->where('user_id', Auth::id());

        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $events = $query->latest()->paginate(5);

        return view('setting.index', compact('events'));
    }

    /**
     * Menampilkan form untuk mengedit event.
     */
    public function edit(Event $event): View
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('setting.edit', compact('event'));
    }

    /**
     * Memperbarui data event dan mengarahkan kembali ke halaman setting.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        // Otorisasi ini penting agar user tidak bisa mengedit event milik orang lain.
        // Error 403 terjadi jika URL salah dan route-model binding gagal.
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $dataToUpdate = $request->except('photo_url', '_token', '_method');

        if ($request->hasFile('photo_url')) {
            if ($event->photo_url) {
                Storage::disk('public')->delete($event->photo_url);
            }
            $path = $request->file('photo_url')->store('event_photos', 'public');
            $dataToUpdate['photo_url'] = $path;
        }

        $event->update($dataToUpdate);

        // Arahkan kembali ke halaman setting index setelah berhasil
        return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
    }

    // ... (method gallery, uploadPhoto, deletePhoto Anda yang lain) ...
    
    public function gallery(Event $event): View
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        $photos = EventPhoto::where('event_id', $event->id)->latest()->paginate(10);
        return view('setting.gallery', compact('event', 'photos'));
    }

    public function uploadPhoto(Request $request, Event $event): RedirectResponse
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate(['photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5000']);
        $path = $request->file('photo')->store('event_photos', 'public');
        EventPhoto::create(['user_id' => Auth::id(), 'event_id' => $event->id, 'path' => $path]);
        return back()->with('success', 'Foto berhasil diunggah ke galeri!');
    }

    public function deletePhoto(EventPhoto $photo): RedirectResponse
    {
        if ($photo->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
        return back()->with('success', 'Foto berhasil dihapus dari galeri.');
    }
}
