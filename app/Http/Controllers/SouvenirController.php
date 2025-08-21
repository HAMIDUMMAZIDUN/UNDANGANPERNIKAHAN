<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SouvenirController extends Controller
{
    /**
     * Menampilkan halaman penukaran souvenir.
     */
    public function index(): View
    {
        // Anda bisa mengambil data dari database di sini
        $souvenirs = [];

        return view('souvenir.index', compact('souvenirs'));
    }
}
