<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\EventPhoto;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UndanganController extends Controller
{
    /**
     * Menampilkan halaman undangan online untuk tamu SPESIFIK.
     */
    public function show(Request $request, Event $event, Guest $guest): View
    {
        $photos = $event->photos()->latest()->get();
        $rsvps = $event->rsvps()->latest()->paginate(5);
        $isPreview = $request->has('preview');
        
        // Memuat view 'undangan.show' untuk tamu pribadi
        return view('undangan.show', compact('event', 'guest', 'photos', 'rsvps', 'isPreview'));
    }

    /**
     * Menampilkan halaman undangan online versi UMUM (untuk grup).
     */
    public function showPublic(Event $event): View
{
    $photos = $event->photos()->latest()->get();
    $rsvps = $event->rsvps()->latest()->paginate(5);

    // BUAT OBJEK GUEST KOSONG SEBAGAI PENGGANTI
    $guest = new Guest();

    // Memuat view 'undangan.public' untuk undangan umum
    return view('undangan.public', compact('event', 'photos', 'rsvps', 'guest'));
}
public function store(Request $request, Event $event): RedirectResponse
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string',
            'status'  => 'required|in:Hadir,Tidak Hadir', // Sesuaikan value dengan di form Anda
        ]);

        // 2. Simpan data ke dalam database
        Rsvp::create([
            'event_id' => $event->id,          // Ambil ID dari event yang dibuka
            'user_id'  => $event->user_id,     // Ambil ID pemilik event
            'name'     => $validatedData['name'],
            'message'  => $validatedData['message'],
            'status'   => strtolower(str_replace(' ', '_', $validatedData['status'])), // Mengubah "Tidak Hadir" -> "tidak_hadir"
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Terima kasih, ucapan dan konfirmasi Anda telah terkirim!');
    }
}
