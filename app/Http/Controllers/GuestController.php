<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use App\Exports\GuestsExport;
use App\Imports\GuestsImport;
use Maatwebsite\Excel\Facades\Excel;

class GuestController extends Controller
{
    /**
     * 1. LIST TAMU
     * Menampilkan semua tamu (halaman utama).
     */
    public function index()
    {
        $guests = Guest::all();
        return view('user.list_tamu.index', compact('guests'));
    }

    // --- FITUR EKSPOR ---
    public function export() 
    {
        return Excel::download(new GuestsExport, 'daftar_tamu.xlsx');
    }

    // --- FITUR IMPOR ---
    public function import(Request $request) 
    {
        // Validasi file harus Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // Proses impor
        Excel::import(new GuestsImport, $request->file('file'));
        
        // Kembali ke halaman dengan pesan sukses
        return back()->with('success', 'Data tamu berhasil diimpor!');
    }

    /**
     * 2. SERVER 1
     * Menampilkan halaman khusus Server 1.
     */
    public function server1()
    {
        $guests = Guest::all(); 
        return view('user.server_1.index', compact('guests'));
    }

    /**
     * 3. SERVER 2
     * Menampilkan halaman khusus Server 2.
     */
    public function server2()
    {
        $guests = Guest::all();
        return view('user.server_2.index', compact('guests'));
    }

    /**
     * 4. TAMU HADIR (GABUNGAN)
     * Menghitung statistik kehadiran dari Server 1 & 2.
     */
    public function attendance()
    {
        // Ambil data tamu yang sudah check-in (Hadir)
        $guests = Guest::whereNotNull('check_in_at')
                       ->orderBy('check_in_at', 'desc')
                       ->get(); 

        // Hitung Statistik SERVER 1
        $server1_data = $guests->where('server_number', 1);
        $s1_invites   = $server1_data->count();
        $s1_pax       = $server1_data->sum('pax');

        // Hitung Statistik SERVER 2
        $server2_data = $guests->where('server_number', 2);
        $s2_invites   = $server2_data->count();
        $s2_pax       = $server2_data->sum('pax');

        // Hitung TOTAL
        $total_invites = $s1_invites + $s2_invites;
        $total_pax     = $s1_pax + $s2_pax;
        
        return view('user.list_tamu_hadir.index', compact(
            'guests', 
            's1_invites', 's1_pax', 
            's2_invites', 's2_pax', 
            'total_invites', 'total_pax'
        ));
    }

    // Method bawaan Resource (Biarkan kosong jika tidak dipakai agar tidak error route)
    public function create() {}
    public function store(Request $request) {}
    public function show(Guest $guest) {}
    public function edit(Guest $guest) {}
    public function update(Request $request, Guest $guest) {}
    public function destroy(Guest $guest) {}
}