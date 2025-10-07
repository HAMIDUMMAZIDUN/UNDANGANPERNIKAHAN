<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan halaman formulir untuk meminta tautan reset kata sandi.
     * Metode ini cocok dengan rute: Route::get('/forgot-password', 'showForgotForm')
     *
     * @return \Illuminate\View\View
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Menangani permintaan pengiriman tautan reset kata sandi yang masuk.
     * Metode ini cocok dengan rute: Route::post('/forgot-password', 'sendResetLink')
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(Request $request)
    {
        // 1. Validasi input email
        $request->validate(['email' => 'required|email']);

        // 2. Mencoba mengirim tautan reset
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // 3. Memberikan respon berdasarkan status pengiriman
        if ($status == Password::RESET_LINK_SENT) {
            // Jika berhasil, kembali ke halaman sebelumnya dengan pesan sukses
            return back()->with('status', __($status));
        }

        // Jika gagal, kembali ke halaman sebelumnya dengan pesan error
        return back()->withErrors(['email' => __($status)]);
    }
}
