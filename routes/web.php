<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SocialLoginController;

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

// Dashboard setelah login
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth');

Route::get('/login/{provider}', function ($provider) {
    return "Login via {$provider} belum diaktifkan.";
})->name('social.login');

Route::get('/login/{provider}', [SocialLoginController::class, 'redirect'])->name('social.login');
Route::get('/login/{provider}/callback', [SocialLoginController::class, 'callback']);
