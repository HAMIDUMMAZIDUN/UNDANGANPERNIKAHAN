<?php

namespace App\Http\Controllers;

use App\Models\User; use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Access\Response as AuthorizationResponse; 
class SettingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan daftar event milik user yang sedang login.
     */
    public function index(Request $request): View
    {
        // Mendapatkan semua event yang dimiliki user saat ini
        // Menggunakan withCount untuk efisiensi query
        $events = Auth::user()->events()->withCount('guests');

        // Menambahkan filter pencarian jika ada
        if ($search = $request->query('search')) {
            $events->where('name', 'LIKE', "%{$search}%");
        }

        // Mengambil data dengan pagination
        $events = $events->latest()->paginate(5);

        return view('user.setting.index', compact('events'));
    }

    /**
     * Menampilkan form untuk mengedit event.
     */
    public function edit(Event $event): View
    {
        // Otorisasi menggunakan Policy: Pastikan user berhak melihat event ini
        $this->authorize('view', $event);

        return view('user.setting.edit', compact('event'));
    }

    /**
     * Memperbarui data event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        // Otorisasi menggunakan Policy: Pastikan user berhak memperbarui event ini
        $this->authorize('update', $event);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'photo_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5000'],
            'groom_name' => ['nullable', 'string', 'max:255'],
            'groom_parents' => ['nullable', 'string', 'max:255'],
            'groom_instagram' => ['nullable', 'string', 'max:255'],
            'groom_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'bride_name' => ['nullable', 'string', 'max:255'],
            'bride_parents' => ['nullable', 'string', 'max:255'],
            'bride_instagram' => ['nullable', 'string', 'max:255'],
            'bride_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5000'],
            'akad_location' => ['nullable', 'string', 'max:255'],
            'akad_time' => ['nullable', 'string', 'max:255'],
            'akad_maps_url' => ['nullable', 'url', 'max:255'],
            'resepsi_location' => ['nullable', 'string', 'max:255'],
            'resepsi_time' => ['nullable', 'string', 'max:255'],
            'resepsi_maps_url' => ['nullable', 'url', 'max:255'],
        ]);

        $this->handleFileUpload($request, $event, 'photo_url', 'event_photos');
        $this->handleFileUpload($request, $event, 'groom_photo', 'groom_photos');
        $this->handleFileUpload($request, $event, 'bride_photo', 'bride_photos');

        $event->fill($request->except(['_token', '_method', 'photo_url', 'groom_photo', 'bride_photo']));

        $event->save();

        return redirect()->route('user.setting.index')->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman galeri foto untuk event tertentu.
     */
    public function gallery(Event $event): View
    {
        $this->authorize('manageGallery', $event);
        
        $photos = $event->photos()->latest()->paginate(12);

        return view('user.setting.gallery', compact('event', 'photos'));
    }

    /**
     * Mengunggah foto baru ke galeri event.
     */
    public function uploadPhoto(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('uploadPhoto', $event);

        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5000']
        ]);

        $path = $request->file('photo')->store('gallery_photos', 'public');

        $event->photos()->create([
            'path' => $path,
            'user_id' => $event->user_id,
        ]);

        return back()->with('success', 'Foto berhasil diunggah!');
    }

    /**
     * Menghapus foto dari galeri.
     */
    public function deletePhoto(EventPhoto $photo): RedirectResponse
    {
     $this->authorize('deletePhoto', $photo);

        Storage::disk('public')->delete($photo->path);

        // Hapus record dari database
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus!');
    }

    /**
     * Helper function untuk mengelola proses upload dan update foto.
     */
    protected function handleFileUpload(Request $request, $model, $fileInputName, $directory)
    {
        if ($request->hasFile($fileInputName)) {
            // Hapus foto lama jika ada
            if ($model->{$fileInputName} && Storage::disk('public')->exists($model->{$fileInputName})) {
                Storage::disk('public')->delete($model->{$fileInputName});
            }

            // Simpan foto baru dan update path
            $model->{$fileInputName} = $request->file($fileInputName)->store($directory, 'public');
        }
    }
}