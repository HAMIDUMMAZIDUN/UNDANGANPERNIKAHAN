<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;

class RsvpController extends Controller
{
    /**
     * Menyimpan data RSVP baru atau memperbarui yang sudah ada.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        // 1. Validasi data yang masuk dari form
        $validated = $request->validate([
            'guest_uuid' => 'required|uuid|exists:guests,uuid',
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'status' => 'required|in:Hadir,Tidak Hadir',
        ]);

        // Cari tamu berdasarkan UUID yang disembunyikan di form
        $guest = Guest::where('uuid', $validated['guest_uuid'])->firstOrFail();

        // 2. Gunakan updateOrCreate untuk menyimpan atau memperbarui RSVP
        // Ini mencegah satu tamu mengirim ucapan berkali-kali.
        Rsvp::updateOrCreate(
            [
                'event_id' => $event->id,
                'guest_id' => $guest->id,
            ],
            [
                'user_id' => $event->user_id,
                'name' => $validated['name'],
                'message' => $validated['message'],
                'status' => $validated['status'],
            ]
        );

        // 3. Redirect kembali ke halaman undangan dengan pesan sukses
        // withFragment akan otomatis scroll ke bagian buku tamu
        return back()->with('success', 'Terima kasih! Ucapan Anda telah berhasil dikirim.')
                     ->withFragment('wedding-wishes');
    }
}