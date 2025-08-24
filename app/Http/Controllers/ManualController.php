<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;

class ManualController extends Controller
{
    /**
     * Menampilkan halaman form check-in manual.
     */
    public function index(): View
    {
        return view('manual.index');
    }

    /**
     * Menyimpan data check-in tamu manual baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi data yang masuk sesuai dengan form di view
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'affiliation' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        // Jika user tidak memiliki event, kembalikan dengan error
        if (!$event) {
            return back()->with('error', 'Tidak ada event aktif untuk melakukan check-in.');
        }

        // 2. Simpan data sebagai tamu baru
        Guest::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'name' => $validatedData['name'],
            'affiliation' => $validatedData['affiliation'],
            'check_in_time' => now(), 
            'number_of_guests' => $validatedData['guest_count'],
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('kehadiran.index')->with('success', 'Tamu manual berhasil check-in!');
    }
}
