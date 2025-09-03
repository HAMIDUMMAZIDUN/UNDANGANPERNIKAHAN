<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // <-- Jangan lupa import Cache

class AdminSettingController extends Controller
{
    /**
     * Mengubah status buka/tutup pesanan.
     */
    public function toggleOrderStatus(Request $request)
    {
        // Ambil status saat ini, defaultnya 'true' (terbuka) jika belum ada
        $currentStatus = Cache::get('open_for_order', true);

        // Balikkan nilainya (jika true jadi false, jika false jadi true)
        Cache::put('open_for_order', !$currentStatus);

        // Tentukan pesan feedback
        $newStatus = !$currentStatus ? 'dibuka' : 'ditutup';
        $message = "Server untuk pemesanan berhasil {$newStatus}.";

        // Kembali ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', $message);
    }
}