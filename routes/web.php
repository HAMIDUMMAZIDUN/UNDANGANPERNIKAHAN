<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GuestbookController;
use Illuminate\Support\Facades\Auth;
use App\Models\Guest; // 1. PENTING: Import Model Guest

Route::get('/', function () {
    return view('welcome');
});

// --- LOGIC REDIRECT DASHBOARD ---
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    
    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// --- GROUP ROUTE ADMIN ---
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');
});


// --- GROUP ROUTE USER (YANG DIPERBAIKI) ---
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        // 2. PERBAIKAN: Ambil data tamu dulu sebelum load view
        $guests = Guest::all(); 

        // 3. Kirim data ke view menggunakan compact
        return view('user.dashboard.index', compact('guests')); 
    })->name('dashboard');
});


// --- GLOBAL AUTH ROUTES ---
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Guests CRUD
    Route::resource('guests', GuestController::class);
    
    // Guestbook Dashboard (Excel Style)
    Route::get('/guestbook', [GuestbookController::class, 'index'])->name('guestbook.index');

    // Scanning
    Route::get('/scan', function () {
        return view('user.scan'); 
    })->name('scan.page');
    
    Route::get('/guests/{id}/barcode', [GuestController::class, 'barcode'])->name('guests.barcode');
    Route::post('/api/scan', [AttendanceController::class, 'scan'])->name('scan.process');
});

require __DIR__.'/auth.php';