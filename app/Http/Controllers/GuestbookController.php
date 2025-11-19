<?php

namespace App\Http\Controllers;

use App\Models\Guest; // 1. Pastikan Model Guest di-import
use Illuminate\Http\Request;

class GuestbookController extends Controller
{
    public function index()
    {
        // 2. Ambil data dari database
        $guests = Guest::all(); 
        
        // 3. Kirim data '$guests' ke view menggunakan compact()
        // Pastikan path view sesuai dengan folder: resources/views/user/dashboard/guestbook.blade.php
        return view('user.dashboard.index', compact('guests'));
    }
}