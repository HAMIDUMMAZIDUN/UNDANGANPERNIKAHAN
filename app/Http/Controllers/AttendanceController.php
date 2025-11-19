<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scan(Request $request)
    {
        // 1. Validasi input tidak boleh kosong
        $request->validate([
            'barcode_code' => 'required|string'
        ]);

        // 2. Cari tamu berdasarkan kode barcode DAN pastikan milik user yang login
        $guest = Guest::where('barcode_code', $request->barcode_code)
                      ->where('user_id', Auth::id()) // Keamanan: hanya cek tamu acaranya sendiri
                      ->first();

        // 3. Jika tamu ditemukan
        if ($guest) {
            // Cek apakah tamu sudah pernah scan sebelumnya (Opsional)
            if ($guest->arrived_at) {
                 return response()->json([
                    'status' => 'error', // Atau 'warning'
                    'message' => 'Tamu a.n ' . $guest->name . ' sudah masuk sebelumnya.'
                ]);
            }

            // Update waktu kehadiran
            $guest->update([
                'arrived_at' => Carbon::now()
            ]);

            // Catat aktivitas (jika fitur log aktif)
            // ActivityLog::create([...]); 

            return response()->json([
                'status' => 'success',
                'message' => 'Selamat Datang, ' . $guest->name . '!'
            ]);
        }

        // 4. Jika tamu TIDAK ditemukan
        return response()->json([
            'status' => 'error',
            'message' => 'Data tamu tidak ditemukan / Kode salah.'
        ], 404);
    }
}