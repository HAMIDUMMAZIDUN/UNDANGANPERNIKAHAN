<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CariTamuController;
use App\Http\Controllers\CheckinController;
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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth & Public Routes
Route::get('/', fn() => view('auth.login'));
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Tamu
    Route::resource('tamu', TamuController::class);
    Route::post('tamu/import', [TamuController::class, 'import'])->name('tamu.import');
    Route::get('tamu/template', [TamuController::class, 'downloadTemplate'])->name('tamu.import.template');
    Route::get('/tamu/{guest}/edit', [TamuController::class, 'edit'])->name('tamu.edit');
    Route::put('/tamu/{guest}', [TamuController::class, 'update'])->name('tamu.update');
    Route::delete('/tamu/{guest}', [TamuController::class, 'destroy'])->name('tamu.destroy');
    Route::get('/tamu/{uuid}/print-qr', [TamuController::class, 'printQr'])->name('tamu.print_qr');
    // Kehadiran
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/kehadiran/export/pdf', [KehadiranController::class, 'exportPdf'])->name('kehadiran.export.pdf');
    Route::get('/kehadiran/export/excel', [KehadiranController::class, 'exportExcel'])->name('kehadiran.export.excel');

    // RSVP
    Route::controller(ReservasiController::class)->prefix('rsvp')->name('rsvp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
        Route::delete('/{rsvp}', 'destroy')->name('destroy');
    });

    // Manual Check-in
    Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
    Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');

    // Souvenir
    Route::controller(SouvenirController::class)->prefix('souvenir')->name('souvenir.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/scan', 'scan')->name('scan');
        Route::post('/redeem', 'redeem')->name('redeem');
        Route::get('/export', 'exportExcel')->name('export');
    });

    // Gift
    Route::controller(GiftController::class)->prefix('gift')->name('gift.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
    });

    // Pengaturan (Settings)
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

    // Event (Create & Store)
    Route::resource('events', EventController::class)->only(['create', 'store']);
    
    // Check-in Page
    Route::get('/check-in', [CheckinController::class, 'index'])->name('check-in.index');
    Route::post('/check-in/qr-process', [CheckinController::class, 'processQrCheckIn'])->name('check-in.process');
});

// Publicly Accessible Routes
Route::get('/sapa/{event:uuid?}', [SapaController::class, 'index'])->name('sapa.index');
Route::get('/sapa/{event:uuid}/data', [SapaController::class, 'getData'])->name('sapa.data');
Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index');
Route::post('/guests/{guest}/checkin', [CariTamuController::class, 'checkIn'])->name('guests.checkin');
Route::post('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
Route::middleware('auth:sanctum')->get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');
Route::get('/checkin/{guest:uuid}', [CheckinController::class, 'process'])->name('checkin.guest');
Route::get('/undangan/{event:uuid}/{guest:uuid}', [UndanganController::class, 'show'])->name('undangan.show');
