<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;

class CariTamuController extends Controller
{
    /**
     * Menampilkan halaman pencarian tamu dengan hasil dari database.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();
        $guestsQuery = Guest::query();

        if ($event) {
            $guestsQuery->where('event_id', $event->id);

            if ($search = $request->query('search')) {
                $guestsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('affiliation', 'LIKE', "%{$search}%");
                });
            } else {
                $guestsQuery->whereRaw('1=0');
            }
        } else {
            $guestsQuery->whereRaw('1=0');
        }

        $guests = $guestsQuery->paginate(15)->withQueryString();

        return view('cari-tamu.index', compact('guests', 'event'));
    }

    /**
     * Method untuk memproses check-in tamu terdaftar dari modal.
     */
    public function checkIn(Request $request, Guest $guest): RedirectResponse
    {
        $request->validate([
            'jumlah_tamu' => 'required|integer|min:1',
        ]);
        
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        if (!$event || $guest->event_id !== $event->id) {
            return back()->with('error', 'Akses tidak diizinkan.');
        }

        if ($guest->check_in_time !== null) {
            return back()->with('error', 'Tamu sudah check-in.');
        }
        
        $guest->update([
            'check_in_time' => now(),
            'number_of_guests' => $request->jumlah_tamu,
        ]);
        
        $searchQuery = $request->input('search');

        return redirect()->route('cari-tamu.index', ['search' => $searchQuery])
            ->with('success', 'Tamu "' . $guest->name . '" berhasil check-in.');
    }

    /**
     * Menyimpan data check-in tamu manual baru.
     * Logika ini sama dengan ManualController@store.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'nullable|string|max:255', // Buat affiliation opsional
            'guest_count' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        if (!$event) {
            return back()->with('error', 'Tidak ada event aktif untuk melakukan check-in.');
        }
        
        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $validatedData['name'],
            'affiliation' => $validatedData['affiliation'] ?? 'Tamu Manual',
            'number_of_guests' => $validatedData['guest_count'],
            'check_in_time' => now(), 
        ]);
        
        return redirect()->route('cari-tamu.index')->with('success', 'Tamu "' . $validatedData['name'] . '" berhasil check-in secara manual!');
    }
}