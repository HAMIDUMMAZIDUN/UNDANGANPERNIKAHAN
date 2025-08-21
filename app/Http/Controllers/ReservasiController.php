<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservasiController extends Controller
{
    /**
     * Menampilkan halaman ucapan & doa (RSVP).
     *
     * @return View
     */
    public function index(): View
    {
        $rsvps = [];
        return view('rsvp.index', compact('rsvps'));
    }
}
