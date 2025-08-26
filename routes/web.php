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
use App\Http\Controllers\RsvpController;

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
    Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index');
    Route::get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');
    Route::post('/guests/{guestId}/checkin', [CariTamuController::class, 'checkIn'])->name('guests.checkin');
    Route::post('/cari-tamu/store', [CariTamuController::class, 'store'])->name('cari-tamu.store');
    Route::get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');

    // Rute yang berhubungan dengan Event spesifik
    Route::prefix('events/{event:uuid}')->name('events.')->group(function () {
        // --- KELOMPOK TAMU (RESTRUCTURED) ---
        Route::get('/tamu', [TamuController::class, 'index'])->name('tamu.index');
        Route::get('/tamu/create', [TamuController::class, 'create'])->name('tamu.create');
        Route::post('/tamu', [TamuController::class, 'store'])->name('tamu.store');
        
        // Route untuk tamu spesifik
        Route::get('/tamu/{guest:uuid}/edit', [TamuController::class, 'edit'])->name('tamu.edit');
        Route::put('/tamu/{guest:uuid}', [TamuController::class, 'update'])->name('tamu.update');
        Route::delete('/tamu/{guest:uuid}', [TamuController::class, 'destroy'])->name('tamu.destroy');

        // Route untuk fitur tambahan tamu
        Route::post('/tamu/import', [TamuController::class, 'import'])->name('tamu.import');
        Route::get('/tamu/import/template', [TamuController::class, 'downloadTemplate'])->name('tamu.import.template');
        Route::get('/tamu/print-qr', [TamuController::class, 'printMultipleQr'])->name('tamu.print_multiple_qr'); 
        Route::get('tamu/{guest:uuid}/download-qr', [TamuController::class, 'downloadQr'])->name('tamu.download_qr');
    });

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
Route::post('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
Route::get('/checkin/{guest:uuid}', [CheckinController::class, 'process'])->name('checkin.guest');
Route::get('/undangan/{event:uuid}/{guest:uuid}', [UndanganController::class, 'show'])->name('undangan.show');
Route::post('/rsvp/{event:uuid}', [RsvpController::class, 'store'])->name('rsvp.store');
Route::get('/undangan/{event:uuid}', [UndanganController::class, 'showPublic'])->name('undangan.public');
Route::get('/undangan/{event:uuid}/preview', [UndanganController::class, 'preview'])->name('undangan.preview');
Route::get('/undangan/{event:uuid}/gallery', [UndanganController::class, 'gallery'])->name('undangan.gallery');
Route::get('/undangan/{event:uuid}/video', [UndanganController::class, 'video'])->name('undangan.video');
Route::get('/undangan/{event:uuid}/cerita', [UndanganController::class, 'story'])->name('undangan.story');
Route::get('/undangan/{event:uuid}/lokasi', [UndanganController::class, 'location'])->name('undangan.location');
Route::get('/undangan/{event:uuid}/acara', [UndanganController::class, 'events'])->name('undangan.events');
Route::get('/undangan/{event:uuid}/protokol', [UndanganController::class, 'protocols'])->name('undangan.protocols');
Route::get('/undangan/{event:uuid}/ucapan', [UndanganController::class, 'greetings'])->name('undangan.greetings');
Route::post('/undangan/{event:uuid}/ucapan', [UndanganController::class, 'storeGreeting'])->name('undangan.greetings.store');
Route::get('/undangan/{event:uuid}/gift', [UndanganController::class, 'gift'])->name('undangan.gift');
Route::get('/undangan/{event:uuid}/souvenir', [UndanganController::class, 'souvenir'])->name('undangan.souvenir');
Route::post('/undangan/{event:uuid}/souvenir/redeem', [UndanganController::class, 'redeemSouvenir'])->name('undangan.souvenir.redeem');
Route::get('/undangan/{event:uuid}/gift/transfer', [UndanganController::class, 'giftTransferForm'])->name('undangan.gift.transfer.form');
Route::post('/undangan/{event:uuid}/gift/transfer', [UndanganController::class, 'giftTransferProcess'])->name('undangan.gift.transfer.process');
Route::get('/undangan/{event:uuid}/gift/transfer/success', [UndanganController::class, 'giftTransferSuccess'])->name('undangan.gift.transfer.success');