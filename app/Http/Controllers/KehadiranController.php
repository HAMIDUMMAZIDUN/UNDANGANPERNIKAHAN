<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class KehadiranController extends Controller
{
    /**
     * Menampilkan halaman daftar tamu.
     *
     * @return View
     */
    public function index(): View
    {
        // Untuk saat ini, kita akan mengirim array kosong sebagai data.
        // Nantinya, Anda bisa mengambil data dari database di sini.
        // Contoh: $guests = Guest::paginate(10);
        $guests = [];

        // Mengembalikan view 'tamu.index' dan mengirimkan data tamu
        return view('kehadiran.index', compact('guests'));
    }
}
