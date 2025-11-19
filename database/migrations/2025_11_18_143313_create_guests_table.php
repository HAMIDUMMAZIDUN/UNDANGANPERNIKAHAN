<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kita gunakan Schema::dropIfExists agar tabel lama yang salah dihapus dulu
        Schema::dropIfExists('guests');

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // Kolom PENTING untuk Dashboard Excel
            $table->boolean('is_online_invited')->default(false); 
            $table->boolean('is_physical_invited')->default(false);
            // Kolom tambahan jika Anda butuh fitur scan barcode nanti
            $table->string('barcode_code')->nullable(); 
            $table->timestamp('arrived_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};