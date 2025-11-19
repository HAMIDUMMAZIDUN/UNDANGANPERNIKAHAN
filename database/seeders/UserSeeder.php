<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@wedding.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'admin',
        ]);

        // 2. Buat Akun User (Contoh: Mempelai)
        User::create([
            'name' => 'Rizky & Lesti (Mempelai)',
            'email' => 'mempelai@wedding.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'user',
        ]);
    }
}