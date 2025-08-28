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
        // Mengambil data event beserta relasi user-nya
        $query = Event::with('user');

        // Logika untuk filter (contoh: 'complete')
        if ($request->filled('status') && $request->status == 'complete') {
            // Asumsikan 'complete' berarti tanggal event sudah lewat
            $query->where('date', '<', now());
        }

        // Logika untuk pencarian berdasarkan nama event atau nama user
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orderhistory.index', compact('orders'));
    }
}
