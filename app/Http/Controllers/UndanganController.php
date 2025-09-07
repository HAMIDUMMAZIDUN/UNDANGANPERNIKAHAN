<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Pastikan use statement ini ada

class UndanganController extends Controller
{
    /**
     * Menampilkan halaman undangan online untuk tamu SPESIFIK.
     */
    public function show(Request $request, Event $event, Guest $guest): View
    {
        // Pastikan tamu ini milik event yang benar
        if ($guest->event_id !== $event->id) {
            abort(404);
        }

        // === LOGIKA BARU UNTUK MEMILIH TEMPLATE ===
        // Mengganti spasi dengan tanda hubung (-) pada nama template agar cocok dengan nama file
        $templateName = str_replace(' ', '-', $event->template_name);
        $viewPath = 'undangan.templates.' . $templateName;

        // Cek apakah file template-nya ada
        if (!view()->exists($viewPath)) {
            abort(404, "Template undangan '{$templateName}' tidak ditemukan.");
        }
        // === AKHIR LOGIKA BARU ===
        
        $photos = $event->photos()->latest()->get();
        $rsvps = $event->rsvps()->latest()->paginate(5);
        $isPreview = $request->has('preview');
        
        // Menggunakan $viewPath dinamis, bukan 'undangan.show'
        return view($viewPath, compact('event', 'guest', 'photos', 'rsvps', 'isPreview'));
    }

    /**
     * Menampilkan halaman undangan online versi UMUM (untuk grup).
     */
    public function showPublic(Event $event): View
    {
        // === LOGIKA BARU UNTUK MEMILIH TEMPLATE ===
        // Mengganti spasi dengan tanda hubung (-) pada nama template
        $templateName = str_replace(' ', '-', $event->template_name);
        $viewPath = 'undangan.templates.' . $templateName;

        // Cek untuk memastikan file view template tersebut benar-benar ada
        if (!view()->exists($viewPath)) {
            abort(404, "Template undangan '{$templateName}' tidak ditemukan.");
        }
        // === AKHIR LOGIKA BARU ===

        $photos = $event->photos()->latest()->get();
        $rsvps = $event->rsvps()->latest()->paginate(5);

        // Buat objek "tamu umum" karena ini adalah link publik
        $guest = (object) [
            'name' => 'Tamu Undangan',
        ];
        
        // Menggunakan $viewPath dinamis, bukan 'undangan.public'
        return view($viewPath, compact('event', 'photos', 'rsvps', 'guest'));
    }
    
    /**
     * Menyimpan data RSVP dari form undangan.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        // 1. Validasi input dari form
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'message' => 'required|string',
            'status'  => 'required|in:Hadir,Tidak Hadir',
        ]);

        // 2. Simpan data ke dalam database
        Rsvp::create([
            'event_id' => $event->id,
            'user_id'  => $event->user_id,
            'name'     => $validatedData['name'],
            'message'  => $validatedData['message'],
            'status'   => $validatedData['status'], // Tidak perlu mengubah format status lagi
        ]);

        // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Terima kasih, ucapan dan konfirmasi Anda telah terkirim!');
    }
}