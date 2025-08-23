<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse; // <-- TAMBAHKAN INI

class CariTamuController extends Controller
{
    /**
     * Menampilkan halaman pencarian tamu dengan hasil dari database.
     */
    public function index(Request $request): View
    {
        // ... (kode method index Anda yang sudah ada, tidak perlu diubah)
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
     * Method baru untuk memproses check-in manual dari modal.
     * * @param Request $request
     * @param Guest $guest
     * @return RedirectResponse
     */
    public function checkIn(Request $request, Guest $guest): RedirectResponse
    {
        // 1. Validasi input
        $request->validate([
            'jumlah_tamu' => 'required|integer|min:1',
        ]);

        // 2. Cek apakah tamu ini milik event user yang sedang login (keamanan)
        $event = Event::where('user_id', Auth::id())->first();
        if (!$event || $guest->event_id !== $event->id) {
            return back()->with('error', 'Akses tidak diizinkan.');
        }

        // 3. Update data tamu
        $guest->update([
            'check_in_time' => now(), // Set waktu check-in ke waktu sekarang
            'number_of_guests' => $request->jumlah_tamu,
        ]);

        // 4. Kembalikan ke halaman pencarian dengan pesan sukses
        return redirect()->route('cari-tamu.index', ['search' => $request->query('search')])
                         ->with('success', 'Tamu "' . $guest->name . '" berhasil check-in.');
    }
}