<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TamuController extends Controller
{
    public function index(): View
    {
        $guests = Guest::where('user_id', Auth::id())->get();
        return view('tamu.index', compact('guests'));
    }

    public function create(): View
    {
        return view('tamu.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->firstOrFail();

        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $request->name,
            'affiliation' => $request->affiliation,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('tamu.index')->with('success', 'Tamu baru berhasil ditambahkan!');
    }

    public function edit(Guest $tamu): View
    {
        // Pastikan tamu yang diedit milik user yang sedang login
        if ($tamu->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tamu.edit', compact('tamu'));
    }

    public function update(Request $request, Guest $tamu): RedirectResponse
    {
        if ($tamu->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $tamu->update($request->all());

        return redirect()->route('tamu.index')->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $tamu): RedirectResponse
    {
        if ($tamu->user_id !== Auth::id()) {
            abort(403);
        }
        
        $tamu->delete();
        return redirect()->route('tamu.index')->with('success', 'Tamu berhasil dihapus.');
    }
}
