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
Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
Route::get('/rsvp', [ReservasiController::class, 'index'])->name('rsvp.index');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cari-tamu', [CariTamuController::class, 'index'])->name('cari-tamu.index'); 
Route::get('/manual', [ManualController::class, 'index'])->name('manual.index');
Route::post('/manual', [ManualController::class, 'store'])->name('manual.store');
Route::get('/souvenir', [SouvenirController::class, 'index'])->name('souvenir.index');
Route::get('/gift', [GiftController::class, 'index'])->name('gift.index');
Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
Route::get('events/create', [EventController::class, 'create'])->name('events.create');
Route::post('events', [EventController::class, 'store'])->name('events.store');

