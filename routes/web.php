<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController; 
use Illuminate\Support\Facades\Auth;
use App\Models\Guest; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN DEPAN (LANDING PAGE)
Route::get('/', function () {
    return view('welcome');
});

// 2. RUTE LOGIN MANUAL
Route::middleware('guest')->get('/login', function () {
    return view('auth.login'); 
})->name('login');

// 3. LOGIC REDIRECT DASHBOARD
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } 
    
    // User biasa diarahkan ke List Tamu
    return redirect()->route('guests.index');
})->middleware(['auth', 'verified'])->name('dashboard');


// 4. GROUP ROUTE ADMIN
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');
});


// 5. GROUP ROUTE USER / TAMU (UTAMA)
Route::middleware(['auth', 'verified'])->group(function () {

    // A. LIST TAMU & CRUD
    Route::resource('guests', GuestController::class);
    
    // B. FITUR IMPOR & EKSPOR EXCEL
    Route::get('/guests-export', [GuestController::class, 'export'])->name('guests.export');
    Route::post('/guests-import', [GuestController::class, 'import'])->name('guests.import');

    // C. SERVER 1 & 2
    Route::get('/server-1', [GuestController::class, 'server1'])->name('server1');
    Route::get('/server-2', [GuestController::class, 'server2'])->name('server2'); // Pastikan Server 2 ada

    // D. TAMU HADIR (Attendance) & PDF
    Route::get('/tamu-hadir', [GuestController::class, 'attendance'])->name('attendance');
    Route::get('/tamu-hadir/pdf', [GuestController::class, 'exportPdf'])->name('attendance.pdf'); // <-- RUTE PDF BARU

    // E. PROFIL PENGGUNA
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat rute auth bawaan (Login post, Register, Logout, dll)
require __DIR__.'/auth.php';