<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class SettingController extends Controller
{
    /**
     * Menampilkan halaman settings.
     */
    public function index(): View
    {
        // Mengambil semua event milik user yang sedang login, beserta jumlah tamu (guests)
        $events = Event::withCount('guests')
                       ->where('user_id', Auth::id())
                       ->get();

        return view('setting.index', compact('events'));
    }
}
