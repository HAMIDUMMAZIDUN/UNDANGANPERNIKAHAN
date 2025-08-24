<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class CheckInController extends Controller
{
    // Method index() tidak perlu diubah
    public function index()
    {
        return view('check-in.index');
    }

    /**
     * Memproses data dari hasil scan QR (berdasarkan NAMA TAMU).
     */
    public function processQrCheckIn(Request $request)
{
    // 1. Validasi request
    $request->validate(['qr_code' => 'required|url']);

    // 2. Dapatkan URL dari hasil scan
    $invitationUrl = $request->qr_code;
    $guestUuid = null;

    // 3. Ekstrak UUID tamu dari URL menggunakan regular expression
    // Ini akan mencari pola /undangan/.../.../{uuid}
    if (preg_match('/\/([a-f0-9\-]{36})$/', $invitationUrl, $matches)) {
        $guestUuid = $matches[1];
    }
    
    // Jika UUID tidak ditemukan dalam format URL
    if (!$guestUuid) {
        return response()->json(['success' => false, 'message' => 'Format QR Code tidak valid.'], 400); // 400 Bad Request
    }

    // 4. Cari tamu berdasarkan UUID
    $guest = Guest::where('uuid', $guestUuid)->first();

    // Jika tamu tidak ditemukan dengan UUID tersebut
    if (!$guest) {
        return response()->json(['success' => false, 'message' => 'Tamu tidak terdaftar.'], 404); // 404 Not Found
    }
    
    // Periksa apakah event milik user yang login
    if ($guest->event->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403); // 403 Forbidden
    }

    // Periksa apakah tamu sudah pernah check-in
    if ($guest->check_in_time) {
        return response()->json([
            'success' => false, 
            'message' => 'Tamu ' . $guest->name . ' sudah melakukan check-in sebelumnya.'
        ], 409); // 409 Conflict
    }

    // 5. Jika semua validasi lolos, lakukan proses check-in
    $guest->update([
        'check_in_time' => now(),
    ]);

    // 6. Kirim response sukses
    return response()->json([
        'success' => true, 
        'message' => 'Selamat datang, ' . $guest->name . '!',
        'guest_name' => $guest->name
    ]);
}
}