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
// app/Http/Controllers/DashboardController.php


public function index(Request $request): View|RedirectResponse
{
    try {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $event = Event::where('user_id', $user->id)->first();
        if (!$event) {
            return redirect()->route('events.create')
                ->with('info', 'Selamat datang! Silakan buat event pertama Anda untuk memulai.');
        }

        // --- Statistik (Tidak berubah, sudah benar) ---
        $baseGuestsQuery = Guest::where('event_id', $event->id);
        
        $totalUndangan = (clone $baseGuestsQuery)->count();
        $hadirQuery = (clone $baseGuestsQuery)->whereNotNull('check_in_time');
        $jumlahHadir = (clone $hadirQuery)->count(); // Jumlah undangan yang hadir
        $totalOrangHadir = (clone $hadirQuery)->sum('number_of_guests'); // Jumlah orang (termasuk rombongan)
        $totalPotensiOrang = (clone $baseGuestsQuery)->sum('number_of_guests');

        // --- Logika Baru untuk Daftar Tamu ---
        $listQuery = Guest::where('event_id', $event->id);
        
        if ($request->filled('search')) {
            // JIKA MENCARI: Cari dari SEMUA tamu berdasarkan nama
            $searchQuery = $request->input('search');
            $listQuery->where('name', 'like', "%{$searchQuery}%")
                      ->orderBy('name', 'asc'); // Urutkan berdasarkan nama agar mudah dicari
        } else {
            // JIKA TIDAK MENCARI: Hanya tampilkan tamu yang SUDAH HADIR
            $listQuery->whereNotNull('check_in_time')
                      ->orderBy('check_in_time', 'desc'); // Urutkan berdasarkan yang paling baru hadir
        }

        // Lakukan paginasi pada query yang sudah disiapkan
        $guests = $listQuery->paginate(10)->withQueryString();

        return view('dashboard.index', [
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

