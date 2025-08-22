<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user admin yang sudah dibuat sebelumnya
        $adminUser = User::where('email', 'admin@example.com')->first();

        // Jika user admin ditemukan, buat event untuknya
        if ($adminUser) {
            Event::create([
                'user_id' => $adminUser->id,
                'name' => 'Pernikahan Hamid & Pasangan',
                'date' => '2025-11-30',
                'location' => 'Gedung Serbaguna, Jakarta',
                'description' => 'Sebuah acara pernikahan yang indah.',
            ]);
        }
    }
}
