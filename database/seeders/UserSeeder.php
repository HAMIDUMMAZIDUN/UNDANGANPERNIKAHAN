<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@wedding.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'Rizky & Lesti (Mempelai)',
            'email' => 'mempelai@wedding.com',
            'password' => Hash::make('password'), 
            'role' => 'user',
        ]);
    }
}