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
     * Menampilkan halaman undangan online untuk tamu spesifik.
     */
    public function show(Event $event, Guest $guest): View
    {
        // Mengambil data galeri foto untuk event ini
        $photos = EventPhoto::where('event_id', $event->id)->latest()->get();

        // Mengambil data ucapan (RSVP) untuk event ini
        $rsvps = Rsvp::where('event_id', $event->id)->latest()->paginate(5);
        
        return view('undangan.show', compact('event', 'guest', 'photos', 'rsvps'));
    }
}
