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
            // Menambahkan kolom uuid yang unik dan boleh kosong (untuk data lama)
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            //
        });
    }
};
