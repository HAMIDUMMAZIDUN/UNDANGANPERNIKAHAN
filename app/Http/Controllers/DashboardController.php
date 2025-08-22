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
     * Menampilkan halaman dashboard utama dengan data event dan tamu.
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            $user = Auth::user();

            // Mengambil event pertama yang dimiliki oleh user
            // Menggunakan first() agar tidak error jika data kosong
            $event = Event::where('user_id', $user->id)->first();

            // JIKA EVENT TIDAK DITEMUKAN, arahkan ke halaman untuk membuat event
            if (!$event) {
                // Pastikan Anda sudah membuat route 'events.create'
                return redirect()->route('events.create')
                    ->with('info', 'Selamat datang! Silakan buat event pertama Anda untuk memulai.');
            }

            // Kode di bawah ini hanya akan berjalan jika event ditemukan
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

            // Mengembalikan pesan error yang lebih umum
            return redirect()->back()->with('error', 'Terjadi kesalahan pada sistem dashboard.');
        }
    }
}
