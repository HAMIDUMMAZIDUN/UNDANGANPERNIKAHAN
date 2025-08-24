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
}