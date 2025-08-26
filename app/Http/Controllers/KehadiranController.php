<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Exports\GuestsKehadiranExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KehadiranController extends Controller
{
    private function getFilteredGuests(Request $request)
    {
        $query = Guest::where('user_id', Auth::id());

        if ($request->query('filter') === 'tidak-hadir') {
            $query->whereNull('check_in_time');
        } else {
            $query->whereNotNull('check_in_time');
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $userId = Auth::id();
        $baseQuery = Guest::where('user_id', $userId);
        
        // --- PERBAIKAN LOGIKA STATISTIK ---
        $totalUndangan = (clone $baseQuery)->count();
        
        $hadirQuery = (clone $baseQuery)->whereNotNull('check_in_time');
        $totalHadir = (clone $hadirQuery)->count();
        $jumlahTamuHadir = (clone $hadirQuery)->sum('number_of_guests');
        
        // Menambahkan statistik untuk yang tidak hadir
        $totalTidakHadir = (clone $baseQuery)->whereNull('check_in_time')->count();
        // --- AKHIR PERBAIKAN ---

        $query = $this->getFilteredGuests($request);
        $guests = $query->latest('check_in_time')->paginate(15);

        return view('kehadiran.index', compact(
            'totalUndangan',
            'totalHadir',
            'jumlahTamuHadir',
            'totalTidakHadir', // Mengirim data baru ke view
            'guests'
        ));
    }

    public function exportPdf(Request $request)
    {
        $query = $this->getFilteredGuests($request);
        $guests = $query->get();
        $status = $request->query('filter', 'hadir');

        $pdf = Pdf::loadView('kehadiran.pdf', [
            'guests' => $guests,
            'status' => $status === 'tidak-hadir' ? 'Tidak Hadir' : 'Hadir'
        ]);

        return $pdf->download('laporan-kehadiran-'. $status .'-'. date('Y-m-d') .'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $query = $this->getFilteredGuests($request);
        $guests = $query->get();
        $status = $request->query('filter', 'hadir');

        return Excel::download(new GuestsKehadiranExport($guests), 'laporan-kehadiran-'. $status .'-'. date('Y-m-d') .'.xlsx');
    }
}
