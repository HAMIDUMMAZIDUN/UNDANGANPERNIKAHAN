<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPhoto;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SapaController extends Controller
{
    /**
     * Menampilkan halaman utama Layar Sapa.
     * Jika tidak ada event di URL, akan menampilkan event terbaru.
     */
    public function index(Event $event = null): View
    {
        // Jika tidak ada event spesifik yang diberikan di URL,
        // cari event yang paling terakhir diupdate sebagai default.
        if (is_null($event)) {
            $event = Event::latest('updated_at')->first();
        }

        return view('sapa.index', compact('event'));
    }

    /**
     * Menyediakan data (ucapan & foto) untuk di-fetch oleh JavaScript.
     */
    public function getData(Event $event): JsonResponse
    {
        // Mengambil ucapan terbaru dari tamu yang statusnya 'hadir'
        $rsvps = Rsvp::where('event_id', $event->id)
                     ->where('status', 'hadir')
                     ->whereNotNull('message')
                     ->latest()
                     ->limit(15)
                     ->get(['name', 'message']);

        // Mengambil semua path foto untuk event ini
        $photos = EventPhoto::where('event_id', $event->id)
                            ->latest()
                            ->get(['path']);

        // Menggabungkan data untuk slideshow
        $slideshowItems = [];
        foreach ($rsvps as $rsvp) {
            $slideshowItems[] = ['type' => 'rsvp', 'data' => $rsvp];
        }
        foreach ($photos as $photo) {
            $slideshowItems[] = ['type' => 'photo', 'data' => asset('storage/' . $photo->path)];
        }

        // Acak urutan agar lebih dinamis
        shuffle($slideshowItems);

        return response()->json($slideshowItems);
    }
}
