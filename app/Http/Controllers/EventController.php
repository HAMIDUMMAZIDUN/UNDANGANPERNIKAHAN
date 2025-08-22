<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    /**
     * Menampilkan form untuk membuat event baru.
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * Menyimpan event baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
        ]);

        Event::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Event berhasil dibuat!');
    }
}
