<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GiftController extends Controller
{
    /**
     * Menampilkan halaman data pengirim kado digital.
     */
    public function index(): View
    {
        // Anda bisa mengambil data dari database di sini
        $gifts = [];

        return view('gift.index', compact('gifts'));
    }
}
