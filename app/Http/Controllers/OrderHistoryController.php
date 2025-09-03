<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\View\View;

class OrderHistoryController extends Controller
{
    /**
     * Menampilkan halaman riwayat pesanan (event).
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Ambil semua jenis paket unik dari database untuk ditampilkan sebagai tab filter
        $packets = Event::query()->whereNotNull('packet')->distinct()->pluck('packet');

        // Mengambil data event beserta relasi user-nya
        $query = Event::with('user');

        // Filter berdasarkan status 'complete'
        if ($request->filled('status') && $request->status == 'complete') {
            // Asumsikan 'complete' berarti tanggal event sudah lewat
            $query->where('date', '<', now());
        }

        // Filter berdasarkan jenis paket
        if ($request->filled('packet')) {
            $query->where('packet', $request->packet);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        // Logika untuk pencarian berdasarkan nama user
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($userQuery) use ($searchTerm) {
                $userQuery->where('name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Lakukan paginasi dan pastikan semua filter tetap ada saat berpindah halaman
        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.orderhistory.index', compact('orders', 'packets'));
    }
}

