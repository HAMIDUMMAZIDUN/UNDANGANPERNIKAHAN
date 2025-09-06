<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah pengguna sudah login dan status client request terakhirnya adalah 'approved'
        if (Auth::check() && Auth::user()->clientRequests()->where('status', 'approved')->exists()) {
            // Jika sudah disetujui, izinkan akses ke dasbor
            return $next($request);
        }

        // Jika belum disetujui, arahkan kembali ke halaman status
        return redirect()->route('user.status')->with('info', 'Akun Anda sedang dalam peninjauan oleh admin.');
    }
}

