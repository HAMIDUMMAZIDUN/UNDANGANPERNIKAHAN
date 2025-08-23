<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Menambahkan kolom baru untuk waktu check-in
            // nullable() berarti kolom ini boleh kosong (karena tamu belum tentu check-in)
            // after('phone_number') menempatkan kolom ini setelah kolom nomor telepon (opsional, untuk kerapian)
            $table->timestamp('check_in_time')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            // Ini akan dijalankan jika Anda melakukan rollback migrasi
            $table->dropColumn('check_in_time');
        });
    }
};