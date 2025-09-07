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
        // DIUBAH: Mengambil jenis template unik, bukan 'packet'
        $templates = Event::query()->whereNotNull('template_name')->distinct()->pluck('template_name');

        // Mengambil data event beserta relasi user-nya
        $query = Event::with('user')->latest();

        // Filter berdasarkan status 'complete'
        if ($request->filled('status') && $request->status == 'complete') {
            $query->where('date', '<', now());
        }

        // DIUBAH: Filter berdasarkan template_name, bukan 'packet'
        if ($request->filled('template')) {
            $query->where('template_name', $request->template);
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
        
        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orderhistory.index', compact('orders', 'templates'));
    }
}