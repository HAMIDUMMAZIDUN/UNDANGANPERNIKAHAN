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

    // --- A. RUTE API (AJAX) ---
    Route::get('/api/guests/search', [GuestController::class, 'ajaxSearch'])->name('guests.ajax_search');

    // --- B. RUTE KHUSUS (HARUS DI ATAS RESOURCE) ---
    
    // Fitur Hapus Banyak (Bulk Delete)
    Route::delete('/guests/bulk-delete', [GuestController::class, 'bulkDestroy'])->name('guests.bulk_destroy');

    // Fitur Impor & Ekspor Excel
    Route::get('/guests-export', [GuestController::class, 'export'])->name('guests.export');
    Route::post('/guests-import', [GuestController::class, 'import'])->name('guests.import');

    // --- C. LIST TAMU & CRUD (RESOURCE) ---
    Route::resource('guests', GuestController::class);
    
    // --- D. SERVER 1 (SCAN) & SERVER 2 ---
    Route::get('/server-1', [GuestController::class, 'server1'])->name('server1');
    Route::get('/server-2', [GuestController::class, 'server2'])->name('server2');

    // --- E. TAMU HADIR (REKAP & PDF) ---
    Route::get('/tamu-hadir', [GuestController::class, 'attendance'])->name('attendance');
    Route::get('/tamu-hadir/pdf', [GuestController::class, 'exportPdf'])->name('attendance.pdf');

    // --- F. PROFIL PENGGUNA ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';