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
        // Validasi request
        $request->validate(['qr_code' => 'required|string']);

        $guestName = $request->qr_code; // Data dari QR sekarang adalah nama tamu
        $event = Event::where('user_id', Auth::id())->first();

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.'], 404);
        }

        // --- PERUBAHAN LOGIKA UTAMA DI SINI ---
        // Cari tamu berdasarkan NAMA yang belum pernah check-in
        $guest = Guest::where('name', $guestName)
                      ->where('event_id', $event->id)
                      ->whereNull('check_in_time') // <-- Kunci untuk menangani nama duplikat
                      ->first();

        // Jika tidak ada tamu dengan nama tersebut yang belum check-in
        if (!$guest) {
            // Kita cek apakah ada tamu dengan nama itu tapi SUDAH check-in
            $alreadyCheckedIn = Guest::where('name', $guestName)
                                     ->where('event_id', $event->id)
                                     ->whereNotNull('check_in_time')
                                     ->exists();

            if ($alreadyCheckedIn) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tamu ' . $guestName . ' sudah melakukan check-in sebelumnya.'
                ], 409); // 409 Conflict
            }

            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan atau QR Code tidak valid.'], 44);
        }

        // Jika tamu ditemukan dan belum check-in, lakukan proses check-in
        $guest->update([
            'check_in_time' => now(),
            'number_of_guests' => $guest->number_of_guests ?: 1
        ]);

        // Kirim response sukses
        return response()->json([
            'success' => true, 
            'message' => 'Selamat datang, ' . $guest->name . '!',
            'guest_name' => $guest->name
        ]);
    }
}