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
    /**
     * Menampilkan halaman dashboard utama.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }
            
            // LOGIKA PENGECEKAN STATUS PENGGUNA
            // Jika status user BUKAN 'approve', paksa logout dan kembalikan ke halaman login
            if ($user->status !== 'approve') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Akun Anda belum aktif. Harap hubungi admin untuk persetujuan.');
            }

            $event = Event::where('user_id', $user->id)->first();
            if (!$event) {
                return redirect()->route('events.create')
                    ->with('info', 'Selamat datang! Silakan buat event pertama Anda untuk memulai.');
            }

            $baseGuestsQuery = Guest::where('event_id', $event->id);
            
            $totalUndangan = (clone $baseGuestsQuery)->count();
            $hadirQuery = (clone $baseGuestsQuery)->whereNotNull('check_in_time');
            $jumlahHadir = (clone $hadirQuery)->count();
            $totalOrangHadir = (clone $hadirQuery)->sum('number_of_guests'); 
            $totalPotensiOrang = (clone $baseGuestsQuery)->sum('number_of_guests');
            $listQuery = Guest::where('event_id', $event->id);
            
            if ($request->filled('search')) {
                $searchQuery = $request->input('search');
                $listQuery->where('name', 'like', "%{$searchQuery}%")
                          ->orderBy('name', 'asc');
            } else {
                $listQuery->whereNotNull('check_in_time')
                          ->orderBy('check_in_time', 'desc'); 
            }

            $guests = $listQuery->paginate(10)->withQueryString();

            return view('user.dashboard.index', [
                'event' => $event,
                'guests' => $guests,
                'totalUndangan' => $totalUndangan,
                'jumlahHadir' => $jumlahHadir,
                'totalOrangHadir' => $totalOrangHadir,
                'totalPotensiOrang' => $totalPotensiOrang,
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan pada sistem dashboard.');
        }
    }
}

