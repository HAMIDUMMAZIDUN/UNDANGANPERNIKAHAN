<?php

use Illuminate\Support\Facades\Route;

// General Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SapaController;
use App\Http\Controllers\UndanganController;

// User-Specific Feature Controllers
use App\Http\Controllers\CariTamuController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\OrderHistoryController;

// Admin-Specific Controllers
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RequestClientController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// RUTE PUBLIK (Dapat diakses tanpa login)
// =========================================================================

Route::get('/', fn() => view('welcome'))->name('home');

// Autentikasi & Reset Password
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
});
Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showForgotForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update');
});


Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/katalog/demo/{id}', [KatalogController::class, 'showDemo'])->name('katalog.show');
Route::post('/rsvp/{event:uuid}', [ReservasiController::class, 'store'])->name('rsvp.store');
Route::prefix('undangan/{event:uuid}')->name('undangan.')->group(function() {
    Route::get('/', [UndanganController::class, 'showPublic'])->name('public');
    Route::get('/{guest:uuid}', [UndanganController::class, 'show'])->name('show');
});
Route::get('/sapa/{event:uuid?}', [SapaController::class, 'index'])->name('sapa.index');
Route::get('/sapa/{event:uuid}/data', [SapaController::class, 'getData'])->name('sapa.data');

// Rute Pembayaran Publik (dari email), dilindungi oleh Signed URL
Route::get('/payment/{clientRequest}', [PaymentController::class, 'show'])->name('payment.show')->middleware('signed');
Route::post('/payment/{clientRequest}/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm');


// =========================================================================
// RUTE YANG MEMBUTUHKAN AUTENTIKASI
// =========================================================================
Route::middleware('auth')->group(function () {

    // --- AREA STATUS & PEMESANAN (Bisa diakses sebelum approve) ---
    Route::get('/status-akun', [OrderController::class, 'status'])->name('user.status');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/order/start/{template_id}', [OrderController::class, 'startOrder'])->name('order.start');
    Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.process');
    Route::get('/order-history', [OrderHistoryController::class, 'index'])->name('order.history.index');

    // --- AREA PENGGUNA INTI (Hanya bisa diakses SETELAH di-approve admin) ---
    Route::group(['middleware' => 'is_approved'], function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
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

        Route::get('/check-in', [CheckinController::class, 'index'])->name('check-in.index');
        Route::post('/check-in/qr-process', [CheckinController::class, 'processQrCheckIn'])->name('check-in.process');
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

        Route::prefix('user/setting')->name('user.setting.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::prefix('events')->group(function () {
                Route::get('/{event}/edit', [SettingController::class, 'edit'])->name('events.edit');
                Route::put('/{event}', [SettingController::class, 'update'])->name('events.update');
                Route::get('/{event}/gallery', [SettingController::class, 'gallery'])->name('events.gallery');
                Route::post('/{event}/gallery', [SettingController::class, 'uploadPhoto'])->name('events.gallery.upload');
            });
            Route::delete('/gallery/{photo}', [SettingController::class, 'deletePhoto'])->name('gallery.delete');
        });
    });


    // --- AREA ADMIN ---
    Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboard.admin.index');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('client.create');
        Route::post('/clients', [ClientController::class, 'store'])->name('client.store');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('client.destroy');
    });
    Route::get('/request-client', [RequestClientController::class, 'index'])->name('request.client.index');
    Route::patch('/request-client/{clientRequest}', [RequestClientController::class, 'updateStatus'])->name('request.client.updateStatus');
    Route::post('/admin/request-client/{clientRequest}/generate-payment', [RequestClientController::class, 'generatePayment'])->name('admin.request.generatePayment');
    Route::post('/admin/request-client/{clientRequest}/approve', [RequestClientController::class, 'approveRequest'])->name('admin.request.approve');
    Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
        Route::post('/toggle-order-status', [AdminSettingController::class, 'toggleOrderStatus'])->name('toggleOrderStatus');
    });
});

// =========================================================================
// RUTE PUBLIK DENGAN WILDCARD (WAJIB di paling akhir)
// =========================================================================
Route::get('/{event:slug}', [EventController::class, 'publicShow'])->name('events.public.show');

