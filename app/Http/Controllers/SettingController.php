<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $query = Auth::user()->events()->withCount('guests');

        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $events = $query->latest()->paginate(5);

        return view('user.setting.index', compact('events'));
    }

    public function edit(Event $event): View
    {
        $this->authorize('view', $event);
        // Variabel $templates tidak diperlukan lagi di sini
        return view('user.setting.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('update', $event);
        
        // Validasi untuk template_name dihapus dari sini
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'photo_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10000'],
            'groom_name' => ['nullable', 'string', 'max:255'],
            'groom_parents' => ['nullable', 'string', 'max:255'],
            'groom_instagram' => ['nullable', 'string', 'max:255'],
            'groom_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10000'],
            'bride_name' => ['nullable', 'string', 'max:255'],
            'bride_parents' => ['nullable', 'string', 'max:255'],
            'bride_instagram' => ['nullable', 'string', 'max:255'],
            'bride_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10000'],
            'akad_location' => ['nullable', 'string', 'max:255'],
            'akad_time' => ['nullable', 'string', 'max:255'],
            'akad_maps_url' => ['nullable', 'url', 'max:255'],
            'resepsi_location' => ['nullable', 'string', 'max:255'],
            'resepsi_time' => ['nullable', 'string', 'max:255'],
            'resepsi_maps_url' => ['nullable', 'url', 'max:255'],
        ]);

        $event->fill($validated);

        $this->handleFileUpload($request, $event, 'photo_url', 'event_photos');
        $this->handleFileUpload($request, $event, 'groom_photo', 'groom_photos');
        $this->handleFileUpload($request, $event, 'bride_photo', 'bride_photos');
        
        $event->save();

        return redirect()->route('user.setting.index')->with('success', 'Event berhasil diperbarui!');
    }

    public function gallery(Event $event): View
    {
        $this->authorize('manageGallery', $event);
        $photos = $event->photos()->latest()->paginate(12);
        return view('user.setting.gallery', compact('event', 'photos'));
    }

    public function uploadPhoto(Request $request, Event $event): RedirectResponse
    {
        $this->authorize('uploadPhoto', $event);
        $request->validate(['photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10000']]);
        $path = $request->file('photo')->store('gallery_photos', 'public');
        $event->photos()->create(['path' => $path, 'user_id' => $event->user_id]);
        return back()->with('success', 'Foto berhasil diunggah!');
    }

    public function deletePhoto(EventPhoto $photo): RedirectResponse
    {
        $this->authorize('deletePhoto', $photo);
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
        return back()->with('success', 'Foto berhasil dihapus!');
    }

    protected function handleFileUpload(Request $request, $model, $fileInputName, $directory)
    {
        if ($request->hasFile($fileInputName)) {
            if ($model->{$fileInputName} && Storage::disk('public')->exists($model->{$fileInputName})) {
                Storage::disk('public')->delete($model->{$fileInputName});
            }
            $model->{$fileInputName} = $request->file($fileInputName)->store($directory, 'public');
        }
    }
}