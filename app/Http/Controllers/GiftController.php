<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventGift;
use App\Exports\EventGiftsExport;
use Maatwebsite\Excel\Facades\Excel;

class GiftController extends Controller
{
    /**
     * Menampilkan halaman data pengirim kado digital.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();

        // Inisialisasi variabel default
        $totalGifts = 0;
        $totalAmount = 0;
        $gifts = collect(); // Gunakan koleksi kosong sebagai default

        if ($event) {
            // Query dasar untuk semua gift di event ini
            $baseQuery = EventGift::where('event_id', $event->id);

            // 1. Hitung statistik TOTAL dari query dasar (sebelum difilter)
            $totalGifts = (clone $baseQuery)->count();
            $totalAmount = (clone $baseQuery)->sum('amount');

            // 2. Terapkan filter pencarian ke query untuk ditampilkan di tabel
            if ($search = $request->query('search')) {
                $baseQuery->where('sender_name', 'LIKE', "%{$search}%");
            }
            
            // 3. Ambil data final dengan paginasi
            $gifts = $baseQuery->latest()->paginate(15)->withQueryString();
        }

        return view('gift.index', compact('gifts', 'totalGifts', 'totalAmount'));
    }

    /**
     * Menangani ekspor data ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $event = Event::where('user_id', $user->id)->first();
        $giftsToExport = collect(); // Default koleksi kosong

        if ($event) {
            $query = EventGift::where('event_id', $event->id);

            // Terapkan filter pencarian yang sama seperti di halaman index
            if ($search = $request->query('search')) {
                $query->where('sender_name', 'LIKE', "%{$search}%");
            }

            // Ambil semua data yang cocok (tanpa paginasi) untuk diekspor
            $giftsToExport = $query->latest()->get();
        }
        
        return Excel::download(new EventGiftsExport($giftsToExport), 'laporan-kado-digital.xlsx');
    }
}
