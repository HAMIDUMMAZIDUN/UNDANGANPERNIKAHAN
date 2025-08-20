<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TamuController;

// Halaman utama langsung ke login
Route::get('/', function () {
    return view('auth.login'); 
});

// Route login
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

// Dashboard setelah login
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

Route::get('/login/{provider}', function ($provider) {
    return "Login via {$provider} belum diaktifkan.";
})->name('social.login');

Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.login');
Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback']);

Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/tamu', [TamuController::class, 'index'])->middleware('auth');
Route::resource('tamu', TamuController::class)->middleware('auth');

