<?php

use Illuminate\Support\Facades\Route;

// --- Controller Otentikasi ---
// Controller kustom Anda telah ditambahkan
use App\Http\Controllers\CustomLoginController;
// Controller standar yang masih digunakan (untuk reset password & verifikasi email)
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;

// --- Controller Utama & Publik ---
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\UndanganController;
use App\Http\Controllers\SapaController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservasiController;

// --- Controller Profil & Password ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordController;

// --- Controller Dasbor Pengguna ---
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CariTamuController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\SettingController;

// --- Controller Dasbor Admin ---
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RequestClientController;
use App\Http\Controllers\Admin\DesignController;
use App\Http\Controllers\Admin\AdminSettingController;

/*
|--------------------------------------------------------------------------
| Rute Web Aplikasi
|--------------------------------------------------------------------------
*/

// ===================================================================
// RUTE PUBLIK (DAPAT DIAKSES TANPA LOGIN)
// ===================================================================

Route::get('/', fn() => view('welcome'))->name('home');

// --- Rute Otentikasi Kustom (untuk Guest) ---
Route::middleware('guest')->group(function () {
    // Register
    Route::get('register', [CustomLoginController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [CustomLoginController::class, 'register']);

    // Login
    Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomLoginController::class, 'login']);

    // Forgot Password
    Route::get('forgot-password', [CustomLoginController::class, 'showForgotForm'])->name('password.request');
    Route::post('forgot-password', [CustomLoginController::class, 'sendResetLink'])->name('password.email');

    // Reset Password (Tetap menggunakan controller bawaan Laravel)
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});


// --- Rute Katalog & Undangan Publik ---
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/demo/{id}', [KatalogController::class, 'showDemo'])->name('katalog.demo');
Route::get('/katalog/{id}', [KatalogController::class, 'show'])->name('katalog.show');

Route::get('/designs/{design}/demo', [KatalogController::class, 'showDynamicDemo'])->name('katalog.design.demo');

Route::post('/rsvp/{event:uuid}', [ReservasiController::class, 'store'])->name('rsvp.store');

Route::prefix('undangan/{event:uuid}')->name('undangan.')->group(function() {
    Route::get('/', [UndanganController::class, 'showPublic'])->name('public');
    Route::get('/{guest:uuid}', [UndanganController::class, 'show'])->name('show');
});

Route::get('/sapa/{event:uuid?}', [SapaController::class, 'index'])->name('sapa.index');
Route::get('/sapa/{event:uuid}/data', [SapaController::class, 'getData'])->name('sapa.data');

// --- Rute Order & Pembayaran ---
Route::get('/payment/{clientRequest}', [PaymentController::class, 'show'])->name('payment.show')->middleware('signed');
Route::post('/payment/{clientRequest}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');

Route::get('/order/start/{template_id}', [OrderController::class, 'startOrder'])->name('order.start');
Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.process');


// ===================================================================
// RUTE YANG MEMERLUKAN LOGIN (AUTH)
// ===================================================================

Route::middleware('auth')->group(function () {

    // --- Logout ---
    Route::post('logout', [CustomLoginController::class, 'logout'])->name('logout');
    
    // --- Rute Verifikasi Email ---
    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    // --- Rute Utama Pengguna ---
    // DIPERBAIKI: Nama rute diubah agar sesuai standar redirect Laravel
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/status-akun', [OrderController::class, 'status'])->name('user.status');
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history.index');

    // --- Rute Profil Lengkap ---
    Route::prefix('user')->group(function() {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('/password', [PasswordController::class, 'update'])->name('user-password.update');
    });

    // --- Manajemen Event & Tamu ---
    Route::resource('events', EventController::class)->only(['create', 'store']);
    Route::prefix('events/{event:uuid}')->name('events.')->group(function () {
        Route::get('/design', [EventController::class, 'design'])->name('design');
        Route::post('/save-design', [EventController::class, 'saveDesign'])->name('saveDesign');
        Route::controller(TamuController::class)->prefix('tamu')->name('tamu.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{guest:uuid}/edit', 'edit')->name('edit');
            Route::put('/{guest:uuid}', 'update')->name('update');
            Route::delete('/{guest:uuid}', 'destroy')->name('destroy');
            Route::post('/import', 'import')->name('import');
            Route::get('/import/template', 'downloadTemplate')->name('import.template');
            Route::get('/print-qr', 'printMultipleQr')->name('print_multiple_qr');
            Route::get('tamu/{guest:uuid}/download-qr', 'downloadQr')->name('tamu.download_qr');
        });
    });

    // --- Fitur-fitur Event ---
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::post('/check-in/qr-process', [CheckInController::class, 'processQrCheckIn'])->name('check-in.process');
    Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index');
    Route::get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');
    Route::post('/guests/{guestId}/checkin', [CariTamuController::class, 'checkIn'])->name('guests.checkin');
    Route::post('/cari-tamu/store', [CariTamuController::class, 'store'])->name('cari-tamu.store');
    Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
    Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/kehadiran/export/pdf', [KehadiranController::class, 'exportPdf'])->name('kehadiran.export.pdf');
    Route::get('/kehadiran/export/excel', [KehadiranController::class, 'exportExcel'])->name('kehadiran.export.excel');
    Route::controller(ReservasiController::class)->prefix('rsvp')->name('rsvp.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
        Route::delete('/{rsvp}', 'destroy')->name('destroy');
    });
    Route::controller(SouvenirController::class)->prefix('souvenir')->name('souvenir.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/scan', 'scan')->name('scan');
        Route::post('/redeem', 'redeem')->name('redeem');
        Route::get('/export', 'exportExcel')->name('export');
    });
    Route::controller(GiftController::class)->prefix('gift')->name('gift.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'exportExcel')->name('export');
    });

    // --- Pengaturan User & Event ---
    Route::prefix('user/setting')->name('user.setting.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/{event:uuid}/edit', [SettingController::class, 'edit'])->name('edit');
            Route::put('/{event:uuid}', [SettingController::class, 'update'])->name('update');
            Route::get('/{event:uuid}/gallery', [SettingController::class, 'gallery'])->name('gallery');
            Route::post('/{event:uuid}/gallery', [SettingController::class, 'uploadPhoto'])->name('gallery.upload');
        });
        Route::delete('/gallery/{photo}', [SettingController::class, 'deletePhoto'])->name('gallery.delete');
    });

    // --- RUTE ADMIN ---
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
    Route::get('/admin/request-client', [RequestClientController::class, 'index'])->name('request.client.index');
    Route::post('/admin/request-client/{clientRequest}/generate-payment', [RequestClientController::class, 'generatePayment'])->name('request.generatePayment');
    Route::post('/admin/request-client/{clientRequest}/approve', [RequestClientController::class, 'approveRequest'])->name('request.approve');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('client.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('client.store');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
        Route::patch('/clients/{client}/update-status', [ClientController::class, 'updateStatus'])->name('client.updateStatus');
        Route::prefix('design')->name('design.')->group(function () {
            Route::get('/', [DesignController::class, 'index'])->name('index');
            Route::post('/save', [DesignController::class, 'save'])->name('save');
            Route::get('/saved', [DesignController::class, 'showSavedDesigns'])->name('saved_designs');
            Route::get('/{design}/edit', [DesignController::class, 'edit'])->name('edit');
            Route::put('/{design}/update', [DesignController::class, 'update'])->name('update');
            Route::delete('/{design}/delete', [DesignController::class, 'destroy'])->name('destroy');
            Route::get('/{design}/show-preview', [DesignController::class, 'showPreview'])->name('show_preview');
            Route::post('/live-preview', [DesignController::class, 'livePreview'])->name('live_preview');
            Route::post('/upload-image', [DesignController::class, 'uploadImage'])->name('upload_image');
            Route::get('/{design}/export', [DesignController::class, 'export'])->name('export');
            Route::post('/import', [DesignController::class, 'import'])->name('import');
        });
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::post('/toggle-order-status', [AdminSettingController::class, 'toggleOrderStatus'])->name('toggleOrderStatus');
        });
    });

});

// Rute 'catch-all' untuk slug event, harus diletakkan di paling akhir
Route::get('/{event:slug}', [EventController::class, 'publicShow'])->name('events.public.show');
