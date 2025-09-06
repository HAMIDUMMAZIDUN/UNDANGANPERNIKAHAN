<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status == 'approve') {
            return $next($request);
        }

        return redirect()->route('user.status')->with('error', 'Akun Anda belum disetujui oleh Admin.');
    }
}