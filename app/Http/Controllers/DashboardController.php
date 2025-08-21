<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama dengan data event dan tamu.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            // Mengambil user yang sedang login
            $user = Auth::user();

            // Mengambil event pertama yang dimiliki oleh user
            $event = Event::where('user_id', $user->id)->firstOrFail();

            // Query dasar untuk tamu yang terkait dengan event ini
            $guestsQuery = Guest::where('event_id', $event->id);

            // Menghitung statistik
            $totalUndangan = $guestsQuery->count();
            $jumlahHadir = (clone $guestsQuery)->where('status', 'hadir')->count();

            // Menerapkan filter pencarian jika ada
            $searchQuery = $request->input('search');
            if ($searchQuery) {
                $guestsQuery->where('name', 'like', "%{$searchQuery}%");
            }

            // Mengambil daftar tamu yang hadir dengan pagination
            $tamuHadir = (clone $guestsQuery)->where('status', 'hadir')->paginate(10);

            // Mengirim semua data ke view
            return view('dashboard.index', [
                'event' => $event,
                'guests' => $tamuHadir,
                'totalUndangan' => $totalUndangan,
                'jumlahHadir' => $jumlahHadir,
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Nama Tidak Ada: ' . $e->getMessage());
        }
    }
}
