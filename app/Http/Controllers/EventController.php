<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    public function create(): View
    {
        return view('events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // ... (method store Anda yang sudah ada) ...
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
        return view('events.edit', compact('event'));
    }

    /**
     * Memperbarui data event di database.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $event->name = $request->name;
        $event->date = $request->date;

        if ($request->hasFile('photo_url')) {
            $path = public_path('foto-event');
            $filename = 'event-' . $event->id . '.' . $request->file('photo_url')->getClientOriginalExtension();

            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            if ($event->photo_url && File::exists(public_path($event->photo_url))) {
                File::delete(public_path($event->photo_url));
            }

            $request->file('photo_url')->move($path, $filename);
            $event->photo_url = 'foto-event/' . $filename;
        }

        $event->save();

        return redirect()->route('setting.index')->with('success', 'Event berhasil diperbarui!');
    }
}
