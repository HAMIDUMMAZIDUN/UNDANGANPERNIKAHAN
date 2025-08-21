<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; 

class ManualController extends Controller
{
    /**
     * Menampilkan halaman utama dengan tombol untuk membuka modal check-in.
     */
    public function index(): View
    {
        return view('manual.index');
    }

    /**
     * Menyimpan data check-in tamu baru.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi data yang masuk
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jumlah_tamu' => 'required|integer|min:1',
            'is_vip' => 'nullable|boolean',
        ]);

        // 2. Logika untuk menyimpan data ke database
        //    Untuk saat ini, kita hanya akan log data yang masuk.
        //    Gantilah bagian ini dengan logika penyimpanan Anda, contoh:
        //
        //    Guest::create([
        //        'name' => $validatedData['nama'],
        //        'address' => $validatedData['alamat'],
        //        'guest_count' => $validatedData['jumlah_tamu'],
        //        'is_vip' => $request->has('is_vip'),
        //        'checkin_time' => now(),
        //    ]);

        Log::info('Tamu baru check-in:', $validatedData);

        // 3. Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Tamu berhasil check-in!');
    }
}