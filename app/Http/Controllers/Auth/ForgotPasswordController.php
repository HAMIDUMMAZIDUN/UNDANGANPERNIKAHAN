<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Controller ini bertanggung jawab untuk menangani email reset password
    | dan menyertakan trait yang membantu dalam mengirim notifikasi ini.
    |
    */

    use SendsPasswordResetEmails;
}

