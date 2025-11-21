<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // PENTING: Import Facade PDF

class GuestController extends Controller
{
    /**
     * 1. LIST TAMU (Halaman Admin/List)
     */
    public function index(Request $request)
    {
        $query = Guest::query();

        // Logika Pencarian
        if ($request->has('search') && $request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Urutkan berdasarkan Abjad A-Z
        $guests = $query->orderBy('name', 'asc')->get();
        
        return view('user.list_tamu.index', compact('guests'));
    }

    /**
     * 2. SERVER 1 (HALAMAN SCAN BACAAN)
     */
    public function server1(Request $request)
    {
        // Logika Scan / Input
        if ($request->has('search') && $request->filled('search')) {
            $keyword = $request->search;
            
            // Cari tamu (Persis atau Mirip)
            $guest = Guest::where('name', $keyword)->first();
            if (!$guest) {
                $guest = Guest::where('name', 'like', '%' . $keyword . '%')->first();
            }

            if ($guest) {
                // Cek Check-in ganda
                if ($guest->check_in_at) {
                    return redirect()->route('server1')->with('warning', "Tamu '{$guest->name}' SUDAH Check-in sebelumnya pada jam " . \Carbon\Carbon::parse($guest->check_in_at)->format('H:i'));
                }

                // Lakukan Check-in
                $guest->update([
                    'server_number' => 1,       
                    'check_in_at' => now(),     
                    'is_physical_invited' => true 
                ]);

                return redirect()->route('server1')->with('success', "BERHASIL: {$guest->name} Masuk!");
            } else {
                return redirect()->route('server1')->with('error', "GAGAL: Tamu '{$keyword}' TIDAK DITEMUKAN di daftar undangan.");
            }
        }

        // Tampilkan Data Tabel Server 1
        $guests = Guest::where('server_number', 1)
                       ->orderBy('check_in_at', 'desc')
                       ->get(); 
                       
        return view('user.server_1.index', compact('guests'));
    }

    /**
     * 3. SERVER 2
     */
    public function server2()
    {
        $guests = Guest::where('server_number', 2)->orderBy('check_in_at', 'desc')->get();
        return view('user.server_2.index', compact('guests'));
    }

    /**
     * 4. TAMU HADIR (REKAPITULASI HTML)
     */
    public function attendance()
    {
        // Ambil semua tamu yang sudah Check-in
        $guests = Guest::whereNotNull('check_in_at')
                       ->orderBy('check_in_at', 'desc')
                       ->get(); 

        $total_invites = $guests->count();
        $total_pax     = $guests->sum('pax');
        
        return view('user.list_tamu_hadir.index', compact(
            'guests', 'total_invites', 'total_pax'
        ));
    }

    /**
     * 5. DOWNLOAD PDF REKAPITULASI (FITUR BARU)
     */
    public function exportPdf()
    {
        // Ambil data yang sama dengan attendance
        $guests = Guest::whereNotNull('check_in_at')
                       ->orderBy('check_in_at', 'desc')
                       ->get();

        $total_invites = $guests->count();
        $total_pax     = $guests->sum('pax');

        // Load view khusus PDF (Pastikan file resources/views/user/list_tamu_hadir/pdf.blade.php sudah dibuat)
        $pdf = Pdf::loadView('user.list_tamu_hadir.pdf', compact('guests', 'total_invites', 'total_pax'));
        
        // Set ukuran kertas A4 Portrait
        $pdf->setPaper('A4', 'portrait');

        // Download file
        return $pdf->download('rekap_kehadiran_tamu.pdf');
    }

    // --- FITUR CRUD & EXCEL ---

    public function update(Request $request, Guest $guest)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pax' => 'required|integer|min:1',
        ]);

        $guest->update([
            'name' => $request->name,
            'pax' => $request->pax,
            'is_online_invited' => $request->has('is_online_invited'),
            'is_physical_invited' => $request->has('is_physical_invited'),
        ]);

        return back()->with('success', 'Data tamu berhasil diperbarui!');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return back()->with('success', 'Data tamu berhasil dihapus!');
    }

    public function export() 
    {
        return Excel::download(new GuestsExport, 'daftar_tamu.xlsx');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new GuestsImport, $request->file('file'));
        
        return back()->with('success', 'Data tamu berhasil diimpor!');
    }

    // Method Resource Bawaan
    public function create() {}
    public function store(Request $request) {}
    public function show(Guest $guest) {}
    public function edit(Guest $guest) {}
}