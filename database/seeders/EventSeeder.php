<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str; // <-- Tambahkan ini

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
            $eventName = 'Pernikahan Hamid & Pasangan';

            Event::create([
                'user_id' => $adminUser->id,
                'name' => $eventName,
                'slug' => Str::slug($eventName, '-'), 
                'uuid' => Str::uuid(),                
                'date' => '2025-11-30',
                'location' => 'Gedung Serbaguna, Jakarta',
                'description' => 'Sebuah acara pernikahan yang indah.',
            ]);
        }
    }
}
