<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CariTamuController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SapaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderHistoryController; 
use App\Http\Controllers\RequestClientController; 


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// Rute Autentikasi & Halaman Awal (Publik)
// =========================================================================
Route::get('/', fn() => view('auth.login'))->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// =========================================================================
// Rute yang Membutuhkan Login (Area Terproteksi)
// =========================================================================
Route::middleware('auth')->group(function () {
    // --- DASBOR PENGGUNA BIASA ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // --- DASBOR ADMIN ---
    Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
    Route::get('/request-client', [RequestClientController::class, 'index'])->name('request.client.index');

    // --- MANAJEMEN KLIEN (ADMIN) ---
    Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history.index');
    Route::get('/request-client', [RequestClientController::class, 'index'])->name('request.client.index');
    Route::patch('/request-client/{clientRequest}', [RequestClientController::class, 'updateStatus'])->name('request.client.updateStatus');

    // --- Rute Umum untuk User Terautentikasi ---
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Pencarian Tamu & Check-in Manual
    Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index');
    Route::get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');
    Route::post('/guests/{guestId}/checkin', [CariTamuController::class, 'checkIn'])->name('guests.checkin');
    Route::post('/cari-tamu/store', [CariTamuController::class, 'store'])->name('cari-tamu.store');

    // Manajemen Event
    Route::resource('events', EventController::class)->only(['create', 'store']);

    // Grup Rute untuk Event Spesifik
    Route::prefix('events/{event:uuid}')->name('events.')->group(function () {
        // Manajemen Tamu
        Route::get('/tamu', [TamuController::class, 'index'])->name('tamu.index');
        Route::get('/tamu/create', [TamuController::class, 'create'])->name('tamu.create');
        Route::post('/tamu', [TamuController::class, 'store'])->name('tamu.store');
        Route::get('/tamu/{guest:uuid}/edit', [TamuController::class, 'edit'])->name('tamu.edit');
        Route::put('/tamu/{guest:uuid}', [TamuController::class, 'update'])->name('tamu.update');
        Route::delete('/tamu/{guest:uuid}', [TamuController::class, 'destroy'])->name('tamu.destroy');

        // Fitur Tambahan Tamu
        Route::post('/tamu/import', [TamuController::class, 'import'])->name('tamu.import');
        Route::get('/tamu/import/template', [TamuController::class, 'downloadTemplate'])->name('tamu.import.template');
        Route::get('/tamu/print-qr', [TamuController::class, 'printMultipleQr'])->name('tamu.print_multiple_qr');
        Route::get('tamu/{guest:uuid}/download-qr', [TamuController::class, 'downloadQr'])->name('tamu.download_qr');
    });

    // Laporan Kehadiran
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/kehadiran/export/pdf', [KehadiranController::class, 'exportPdf'])->name('kehadiran.export.pdf');
    Route::get('/kehadiran/export/excel', [KehadiranController::class, 'exportExcel'])->name('kehadiran.export.excel');

    // Laporan RSVP
    Route::controller(ReservasiController::class)->prefix('rsvp')->name('rsvp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
        Route::delete('/{rsvp}', 'destroy')->name('destroy');
    });

    // Check-in Manual
    Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
    Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');

    // Laporan Souvenir
    Route::controller(SouvenirController::class)->prefix('souvenir')->name('souvenir.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/scan', 'scan')->name('scan');
        Route::post('/redeem', 'redeem')->name('redeem');
        Route::get('/export', 'exportExcel')->name('export');
    });

    // Laporan Gift
    Route::controller(GiftController::class)->prefix('gift')->name('gift.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
    });

    // Pengaturan
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/{event}/edit', [SettingController::class, 'edit'])->name('edit');
            Route::put('/{event}', [SettingController::class, 'update'])->name('update');
            Route::get('/{event}/gallery', [SettingController::class, 'gallery'])->name('gallery');
            Route::post('/{event}/gallery', [SettingController::class, 'uploadPhoto'])->name('gallery.upload');
        });
        Route::delete('/gallery/{photo}', [SettingController::class, 'deletePhoto'])->name('gallery.delete');
    });

    // Check-in via QR Scan
    Route::get('/check-in', [CheckinController::class, 'index'])->name('check-in.index');
    Route::post('/check-in/qr-process', [CheckinController::class, 'processQrCheckIn'])->name('check-in.process');
});


// =========================================================================
// Rute Publik (Dapat diakses tanpa login)
// =========================================================================

// Layar Sapa (Greeting Screen)
Route::get('/sapa/{event:uuid?}', [SapaController::class, 'index'])->name('sapa.index');
Route::get('/sapa/{event:uuid}/data', [SapaController::class, 'getData'])->name('sapa.data');

// Undangan Digital
Route::prefix('undangan/{event:uuid}')->name('undangan.')->group(function() {
    Route::get('/', [UndanganController::class, 'showPublic'])->name('public');
    // ... (rute undangan lainnya)
    Route::get('/{guest:uuid}', [UndanganController::class, 'show'])->name('show');
});

// Rute untuk proses RSVP
Route::post('/rsvp/{event:uuid}', [ReservasiController::class, 'store'])->name('rsvp.store');
