<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman settings.
     */
    public function index(): View
    {
        $events = []; 

        return view('setting.index', compact('events'));
    }
}
