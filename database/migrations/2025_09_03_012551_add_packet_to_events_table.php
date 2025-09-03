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
        Schema::table('events', function (Blueprint $table) {
            // Menambahkan kolom 'packet' setelah kolom 'phone_number' (opsional, untuk kerapian)
            // Kolom ini bisa null jika ada event yang tidak memiliki paket.
            $table->string('packet')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('packet');
        });
    }
};
