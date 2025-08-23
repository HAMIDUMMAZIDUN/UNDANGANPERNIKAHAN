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
     */
    public function index(Event $event): View
    {
        return view('sapa.index', compact('event'));
    }

    /**
     * Menyediakan data (ucapan & foto) untuk di-fetch oleh JavaScript.
     * Ini memungkinkan halaman untuk update secara real-time.
     */
    public function getData(Event $event): JsonResponse
    {
        // Mengambil 10 ucapan terbaru dari tamu yang statusnya 'hadir'
        $rsvps = Rsvp::where('event_id', $event->id)
                     ->where('status', 'hadir')
                     ->whereNotNull('message')
                     ->latest()
                     ->limit(10)
                     ->get(['name', 'message']);

        // Mengambil semua path foto untuk event ini
        $photos = EventPhoto::where('event_id', $event->id)
                            ->latest()
                            ->get(['path']);

        // Menggabungkan dan mengacak data untuk slideshow
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
