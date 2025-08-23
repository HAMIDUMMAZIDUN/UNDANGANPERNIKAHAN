<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\GiftController; 
use App\Http\Controllers\ManualController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CariTamuController;
use App\Http\Controllers\SouvenirController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\ReservasiController; 
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SapaController; 


Route::get('/', function () {
    return view('auth.login'); 
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/login/{provider}', function ($provider) {
    return "Login via {$provider} belum diaktifkan.";
})->name('social.login');

Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.login');
Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback']);
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/tamu', [TamuController::class, 'index'])->middleware('auth');
Route::resource('tamu', TamuController::class)->middleware('auth'); Route::post('tamu/import', [TamuController::class, 'import'])->name('tamu.import');
Route::get('tamu/template', [TamuController::class, 'downloadTemplate'])->name('tamu.template');
Route::delete('/tamu/{id}', [TamuController::class, 'destroy'])->name('tamu.destroy');
Route::controller(TamuController::class)->middleware('auth')->group(function () {
Route::get('tamu', 'index')->name('tamu.index');
Route::get('tamu/create', 'create')->name('tamu.create');
Route::post('tamu', 'store')->name('tamu.store');
Route::post('tamu/import', 'import')->name('tamu.import');
Route::get('tamu/import/template', 'downloadTemplate')->name('tamu.import.template');
Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
Route::get('/kehadiran/export/pdf', [KehadiranController::class, 'exportPdf'])->name('kehadiran.export.pdf');
Route::get('/kehadiran/export/excel', [KehadiranController::class, 'exportExcel'])->name('kehadiran.export.excel');
Route::get('/rsvp', [ReservasiController::class, 'index'])->name('rsvp.index');
Route::controller(ReservasiController::class)->middleware('auth')->group(function () {
    Route::get('rsvp', 'index')->name('rsvp.index');
    Route::get('rsvp/export', 'exportExcel')->name('rsvp.export');
    Route::delete('rsvp/{rsvp}', 'destroy')->name('rsvp.destroy');
});
Route::get('/sapa/{event:uuid?}', [App\Http\Controllers\SapaController::class, 'index'])->name('sapa.index');
Route::get('/sapa/{event:uuid}/data', [App\Http\Controllers\SapaController::class, 'getData'])->name('sapa.data');
Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index'); 
Route::middleware('auth:sanctum')->get('/tamu/search', [CariTamuController::class, 'search'])->name('api.tamu.search');
Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');
Route::get('/souvenir', [SouvenirController::class, 'index'])->name('souvenir.index');
 Route::controller(SouvenirController::class)
        ->prefix('souvenir') 
        ->name('souvenir.')   
        ->group(function () {
        
        Route::get('/', 'index')->name('index');
        Route::get('/scan', 'scan')->name('scan');
        Route::post('/redeem', 'redeem')->name('redeem');
        Route::get('/export', 'exportExcel')->name('export'); 
    });
 Route::controller(App\Http\Controllers\GiftController::class)
        ->prefix('gift')
        ->name('gift.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'exportExcel')->name('export'); // <-- Tambahkan ini
    });
Route::controller(SettingController::class)
    ->middleware('auth')
    ->prefix('setting') 
    ->name('setting.') 
    ->group(function () {
    
    Route::get('/', 'index')->name('index'); 
Route::get('/event/{event}/edit', 'edit')->name('events.edit');
Route::put('/event/{event}', 'update')->name('events.update'); 
Route::get('/event/{event}/gallery', 'gallery')->name('gallery');
Route::post('/event/{event}/gallery', 'uploadPhoto')->name('gallery.upload');
Route::delete('/gallery/{photo}', 'deletePhoto')->name('gallery.delete');
});
Route::resource('events', EventController::class)->except(['index', 'show', 'destroy']);
Route::get('events/create', [EventController::class, 'create'])->name('events.create');
Route::post('events', [EventController::class, 'store'])->name('events.store');
Route::get('/checkin/{guest:uuid}', [CheckinController::class, 'process'])->name('checkin.guest');
Route::get('/checkin/{guest:uuid}', [CheckinController::class, 'handleCheckin'])
    ->middleware('auth')
    ->name('checkin.guest');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
        Route::put('event/{event}', 'update')->name('events.update');
    });
    