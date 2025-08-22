<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            $event = Event::where('user_id', $user->id)->first();

            if (!$event) {
                return redirect()->route('events.create')
                    ->with('info', 'Selamat datang! Silakan buat event pertama Anda untuk memulai.');
            }

            // Query dasar untuk SEMUA tamu di event ini
            $baseGuestsQuery = Guest::where('event_id', $event->id);

            // Hitung statistik (ini tetap sama dan sudah benar)
            $totalUndangan = (clone $baseGuestsQuery)->count();
            $jumlahHadir = (clone $baseGuestsQuery)->where('status', 'hadir')->count();

            // [DIUBAH] Ambil SEMUA tamu, bukan hanya yang hadir
            $allGuestsQuery = Guest::where('event_id', $event->id);
            
            // Terapkan filter pencarian jika ada
            if ($request->filled('search')) {
                $searchQuery = $request->input('search');
                $allGuestsQuery->where('name', 'like', "%{$searchQuery}%");
            }

            // [DIUBAH] Ambil data final dengan pagination, urutkan berdasarkan status
            // Tamu yang hadir akan ditampilkan lebih dulu
            $guests = $allGuestsQuery->orderByRaw("CASE WHEN status = 'hadir' THEN 0 ELSE 1 END")
                                     ->latest('updated_at')
                                     ->paginate(10)
                                     ->withQueryString();

            return view('dashboard.index', [
                'event' => $event,
                'guests' => $guests,
                'totalUndangan' => $totalUndangan,
                'jumlahHadir' => $jumlahHadir,
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem dashboard.');
        }
    }
}