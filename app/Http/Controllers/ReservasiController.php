<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\Event; // <-- Penting: Tambahkan ini
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
     * [BARU] Menyimpan data RSVP baru dari form undangan.
     *
     * @param Request $request
     * @param Event $event
     * @return RedirectResponse
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string',
            'status'  => 'required|in:Hadir,Tidak Hadir', // Sesuaikan dengan value di form
        ]);

        // 2. Simpan data ke dalam database
        Rsvp::create([
            'event_id' => $event->id,
            'user_id'  => $event->user_id, // Mengambil user_id dari event terkait
            'name'     => $validatedData['name'],
            'message'  => $validatedData['message'],
            // Mengubah format status agar sesuai dengan database (cth: "Tidak Hadir" -> "tidak_hadir")
            'status'   => strtolower(str_replace(' ', '_', $validatedData['status'])),
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Terima kasih, ucapan Anda telah berhasil dikirim!');
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
