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
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'groom_name' => 'nullable|string|max:255',
            'groom_parents' => 'nullable|string|max:255', 
            'groom_instagram' => 'nullable|string|max:255',
            'groom_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'bride_name' => 'nullable|string|max:255',
            'bride_parents' => 'nullable|string|max:255', 
            'bride_instagram' => 'nullable|string|max:255',
            'bride_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $dataToUpdate = $request->except([
            '_token', '_method', 
            'photo_url', 'groom_photo', 'bride_photo' 
        ]);
        
        // Handle upload foto utama
        if ($request->hasFile('photo_url')) {
            if ($event->photo_url) {
                Storage::disk('public')->delete($event->photo_url);
            }
            $path = $request->file('photo_url')->store('event_photos', 'public');
            $dataToUpdate['photo_url'] = $path;
        }

        // Handle upload foto mempelai pria
        if ($request->hasFile('groom_photo')) {
            if ($event->groom_photo) {
                Storage::disk('public')->delete($event->groom_photo);
            }
            $path = $request->file('groom_photo')->store('groom_photos', 'public');
            $dataToUpdate['groom_photo'] = $path;
        }

        // Handle upload foto mempelai wanita
        if ($request->hasFile('bride_photo')) {
            if ($event->bride_photo) {
                Storage::disk('public')->delete($event->bride_photo);
            }
            $path = $request->file('bride_photo')->store('bride_photos', 'public');
            $dataToUpdate['bride_photo'] = $path;
        }
        
        $event->update($dataToUpdate);

        return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
    }

    // ... (metode lainnya tidak berubah)
}