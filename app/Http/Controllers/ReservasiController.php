<?php

namespace App\Http\Controllers;

use App\Models\Rsvp; // Pastikan Anda memiliki model ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Exports\RsvpsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class ReservasiController extends Controller
{
    /**
     * Menampilkan halaman ucapan & doa (RSVP) dengan data dan statistik.
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $query = Rsvp::where('user_id', $userId);

        // Menghitung Statistik
        $stats = [
            'komentar' => (clone $query)->count(),
            'hadir' => (clone $query)->where('status', 'hadir')->count(),
            'tidak_hadir' => (clone $query)->where('status', 'tidak_hadir')->count(),
            'ragu' => (clone $query)->where('status', 'ragu')->count(),
        ];

        // Menerapkan Filter
        $filter = $request->query('filter');
        if ($filter && $filter !== 'semua') {
            $query->where('status', $filter);
        }

        $rsvps = $query->latest()->paginate(10);

        return view('rsvp.index', compact('rsvps', 'stats'));
    }

    /**
     * Menghapus data RSVP.
     */
    public function destroy(Rsvp $rsvp): RedirectResponse
    {
        // Otorisasi: pastikan RSVP milik user yang sedang login
        if ($rsvp->user_id !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }

        $rsvp->delete();

        return back()->with('success', 'Ucapan berhasil dihapus.');
    }

    /**
     * Mengekspor data RSVP ke file Excel.
     */
    public function exportExcel(Request $request)
    {
        $userId = Auth::id();
        $query = Rsvp::where('user_id', $userId);
        $filter = $request->query('filter');

        if ($filter && $filter !== 'semua') {
            $query->where('status', $filter);
        }

        $rsvps = $query->latest()->get();
        $fileName = 'ucapan-dan-doa-' . ($filter ?? 'semua') . '-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new RsvpsExport($rsvps), $fileName);
    }
}
