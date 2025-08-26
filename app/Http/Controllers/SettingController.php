<?php

namespace App\Http\Controllers;

use App\Models\Event; 
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman settings.
     */
    public function index(Request $request): View
    {
        $query = Event::withCount('guests')->where('user_id', Auth::id());

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
     * Memperbarui data event.
     */
    
public function update(Request $request, Event $event): RedirectResponse
{
    if ($event->user_id !== Auth::id()) {
        abort(403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'date' => 'required|date',
        'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000',
        'groom_name' => 'nullable|string|max:255',
        'groom_parents' => 'nullable|string|max:255', 
        'groom_instagram' => 'nullable|string|max:255',
        'groom_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        'bride_name' => 'nullable|string|max:255',
        'bride_parents' => 'nullable|string|max:255', 
        'bride_instagram' => 'nullable|string|max:255',
        'bride_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:500',
        'akad_location' => 'nullable|string|max:255',
        'akad_time' => 'nullable|string|max:255',
        'akad_maps_url' => 'nullable|url|max:255',
        'resepsi_location' => 'nullable|string|max:255',
        'resepsi_time' => 'nullable|string|max:255',
        'resepsi_maps_url' => 'nullable|url|max:255',
    ]);

    // Simpan data yang sudah divalidasi (tanpa foto)
    $event->update($request->except(['_token', '_method', 'photo_url', 'groom_photo', 'bride_photo']));
    
    // Handle upload foto (logika ini tidak berubah)
    if ($request->hasFile('photo_url')) {
        if ($event->photo_url) Storage::disk('public')->delete($event->photo_url);
        $event->photo_url = $request->file('photo_url')->store('event_photos', 'public');
    }
    if ($request->hasFile('groom_photo')) {
        if ($event->groom_photo) Storage::disk('public')->delete($event->groom_photo);
        $event->groom_photo = $request->file('groom_photo')->store('groom_photos', 'public');
    }
    if ($request->hasFile('bride_photo')) {
        if ($event->bride_photo) Storage::disk('public')->delete($event->bride_photo);
        $event->bride_photo = $request->file('bride_photo')->store('bride_photos', 'public');
    }
    
    // Simpan perubahan event (termasuk path foto baru)
    $event->save();

    return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
}
    /**
     * Menampilkan halaman galeri foto untuk event tertentu.
     */
    public function gallery(Event $event): View
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Asumsi Anda sudah punya relasi 'photos()' di model Event
        $photos = $event->photos()->latest()->paginate(12); 

        return view('setting.gallery', compact('event', 'photos'));
    }

    /**
     * Mengunggah foto baru ke galeri event.
     */
    public function uploadPhoto(Request $request, Event $event): RedirectResponse
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $path = $request->file('photo')->store('gallery_photos', 'public');

        $event->photos()->create(['path' => $path]);

        return back()->with('success', 'Foto berhasil diunggah!');
    }

    /**
     * Menghapus foto dari galeri.
     */
    public function deletePhoto(EventPhoto $photo): RedirectResponse
    {
        // Otorisasi: pastikan foto ini milik event dari user yang login
        if ($photo->event->user_id !== Auth::id()) {
            abort(403);
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($photo->path);

        // Hapus record dari database
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }
}