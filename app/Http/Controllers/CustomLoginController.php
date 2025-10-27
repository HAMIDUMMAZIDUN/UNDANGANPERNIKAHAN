<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Http;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        $secretKey = env('RECAPTCHA_SECRET_KEY');
        $response = $request->input('g-recaptcha-response');
        
        $googleResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'    => $secretKey,
            'response'  => $response,
            'remoteip'  => $request->ip(),
        ])->json();

        if (!isset($googleResponse['success']) || !$googleResponse['success']) {
            return back()->with('error', 'Validasi reCAPTCHA gagal. Silakan coba lagi.');
        }
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            Activity::create([
                'user_id'   => $user->id,
                'description' => 'telah login ke sistem.'
            ]);

            // Logika pengalihan (redirect) berdasarkan Role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin.index');
            }

            // Default untuk 'user'
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
        $request->validate(['email' => 'required|email']);

        $userExists = User::where('email', $request->email)->exists();

        if (!$userExists) {
            return back()->with('error', 'Email tidak tertaut dengan aplikasi ini.');
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
        } else {
            return back()->with('error', __($status));
        }
    }
}
