<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event; // Pastikan Anda mengimpor model Event
use Illuminate\View\View;

class AdminEventController extends Controller
{
    /**
     * Menampilkan daftar semua event dari semua client.
     */
    public function index(Request $request): View
    {
        // Mulai query, ambil relasi 'user' (client) dan hitung jumlah 'guests'
        $query = Event::query()->with('user')->withCount('guests');

        // 1. Filter Pencarian (berdasarkan nama event ATAU nama client)
        if ($search = $request->query('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%") // Cari di nama event
                  ->orWhereHas('user', function($userQuery) use ($search) { // Cari di nama client
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // 2. Filter Tanggal Event
        if ($startDate = $request->query('start_date')) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate = $request->query('end_date')) {
            $query->whereDate('date', '<=', $endDate);
        }

        // Ambil data, urutkan berdasarkan tanggal event terbaru, dan paginasi
        $events = $query->latest('date')->paginate(10);

        // Kirim data ke view
        return view('admin.events.index', compact('events'));
    }
}
