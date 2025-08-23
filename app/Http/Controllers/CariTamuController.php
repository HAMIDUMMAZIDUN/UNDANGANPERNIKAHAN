<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Guest;

class CariTamuController extends Controller
{
    /**
     * Menampilkan halaman pencarian tamu dengan hasil dari database.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        // Menyiapkan query builder, tapi belum mengambil data
        $guestsQuery = Guest::query();

        if ($event) {
            $guestsQuery->where('event_id', $event->id);

            // Terapkan filter pencarian jika ada input
            if ($search = $request->query('search')) {
                $guestsQuery->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('affiliation', 'LIKE', "%{$search}%");
                });
            } else {
                // Jika tidak ada pencarian, jangan tampilkan apa-apa
                $guestsQuery->whereRaw('1=0'); // Trik untuk mengembalikan hasil kosong
            }
        } else {
            // Jika user tidak punya event, jangan tampilkan apa-apa
            $guestsQuery->whereRaw('1=0');
        }

        $guests = $guestsQuery->paginate(15)->withQueryString();

        return view('cari-tamu.index', compact('guests', 'event'));
    }
}
