<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 2. Tambahkan 'g-recaptcha-response' ke validasi
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        // 3. Logika verifikasi reCAPTCHA
        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $response = $request->input('g-recaptcha-response');
        
        $googleResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secretKey,
            'response' => $response,
            'remoteip' => $request->ip(),
        ])->json();

        // Jika verifikasi gagal, kembalikan dengan error
        if (!isset($googleResponse['success']) || !$googleResponse['success']) {
            return back()->with('error', 'Validasi reCAPTCHA gagal. Silakan coba lagi.');
        }

        // --- Logika login Anda yang lama dimulai dari sini ---
        
        // Ambil credentials email dan password saja untuk login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            Activity::create([
                'user_id'     => $user->id,
                'description' => 'telah login ke sistem.'
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin.index');
            }

            return redirect()->route('dashboard.index');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user' 
        ]);

        Auth::login($user);
        return redirect()->route('dashboard.index'); 
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

   public function sendResetLink(Request $request)
    {
        // 1. Validasi bahwa input adalah email yang valid dan wajib diisi
        $request->validate(['email' => 'required|email']);

        // 2. Cek apakah email yang dimasukkan ada di tabel 'users'
        $userExists = User::where('email', $request->email)->exists();

        // 3. Jika email TIDAK ADA di database
        if (!$userExists) {
            // Kembalikan ke halaman sebelumnya dengan pesan error khusus
            return back()->with('error', 'Email tidak tertaut dengan aplikasi ini.');
        }

        // 4. Jika email ADA, lanjutkan proses pengiriman link reset password bawaan Laravel
        $status = Password::sendResetLink($request->only('email'));

        // 5. Berikan response berdasarkan hasil dari proses pengiriman link
        if ($status === Password::RESET_LINK_SENT) {
            // Jika berhasil, kirim pesan sukses
            return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
        } else {
            // Jika gagal karena alasan lain (misal: throttling), kirim pesan error bawaan
            return back()->with('error', __($status));
        }
    }
}

