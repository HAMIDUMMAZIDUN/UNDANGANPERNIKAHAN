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
        Schema::table('rsvps', function (Blueprint $table) {
            // Menambahkan kolom guest_id setelah kolom event_id
            $table->foreignId('guest_id')
                  ->nullable()
                  ->constrained('guests')
                  ->onDelete('cascade')
                  ->after('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            // Hapus foreign key constraint sebelum menghapus kolom
            $table->dropForeign(['guest_id']);
            $table->dropColumn('guest_id');
        });
    }
};
