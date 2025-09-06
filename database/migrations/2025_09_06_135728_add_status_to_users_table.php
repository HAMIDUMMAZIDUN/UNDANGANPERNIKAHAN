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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'status' setelah kolom 'password'
            // Nilai defaultnya 'pending', sehingga pengguna lama akan otomatis punya status ini.
            $table->string('status')->after('password')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kode untuk menghapus kolom jika migration di-rollback
            $table->dropColumn('status');
        });
    }
};
