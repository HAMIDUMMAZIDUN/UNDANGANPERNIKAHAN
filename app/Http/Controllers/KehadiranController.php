<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Exports\GuestsKehadiranExport; // <-- Import class export
use Maatwebsite\Excel\Facades\Excel;   // <-- Import Facade Excel
use Barryvdh\DomPDF\Facade\Pdf;          // <-- Import Facade PDF

class KehadiranController extends Controller
{
    /**
     * Method private untuk mengambil data tamu berdasarkan filter dan pencarian.
     * Ini digunakan oleh index, exportPdf, dan exportExcel agar tidak duplikat kode.
     */
    private function getFilteredGuests(Request $request)
    {
        $query = Guest::where('user_id', Auth::id());

        // Filter: 'hadir' (default) atau 'tidak-hadir'
        if ($request->query('filter') === 'tidak-hadir') {
            $query->whereNull('check_in_time');
        } else {
            $query->whereNotNull('check_in_time');
        }

        // Filter Pencarian berdasarkan nama
        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        return $query;
    }

    /**
     * Menampilkan halaman statistik kehadiran tamu.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();

        // Statistik
        $totalUndangan = Guest::where('user_id', $userId)->count();
        $totalHadir = Guest::where('user_id', $userId)->whereNotNull('check_in_time')->count();
        $jumlahTamuHadir = $totalHadir;

        // Mengambil data tamu dengan filter dan search
        $query = $this->getFilteredGuests($request);
        $guests = $query->latest('check_in_time')->paginate(15);

        return view('kehadiran.index', compact(
            'totalUndangan',
            'totalHadir',
            'jumlahTamuHadir',
            'guests'
        ));
    }

    /**
     * Export data ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredGuests($request);
        $guests = $query->get();
        $status = $request->query('filter', 'hadir'); // default 'hadir'

        $pdf = Pdf::loadView('kehadiran.pdf', [
            'guests' => $guests,
            'status' => $status === 'tidak-hadir' ? 'Tidak Hadir' : 'Hadir'
        ]);

        return $pdf->download('laporan-kehadiran-'. $status .'-'. date('Y-m-d') .'.pdf');
    }

    /**
     * Export data ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $query = $this->getFilteredGuests($request);
        $guests = $query->get();
        $status = $request->query('filter', 'hadir');

        return Excel::download(new GuestsKehadiranExport($guests), 'laporan-kehadiran-'. $status .'-'. date('Y-m-d') .'.xlsx');
    }
}
