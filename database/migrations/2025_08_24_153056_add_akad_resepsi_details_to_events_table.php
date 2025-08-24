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
        $table->string('akad_location')->nullable()->after('location');
        $table->string('akad_time')->nullable()->after('akad_location');
        $table->string('akad_maps_url')->nullable()->after('akad_time');
        $table->string('resepsi_location')->nullable()->after('akad_maps_url');
        $table->string('resepsi_time')->nullable()->after('resepsi_location');
        $table->string('resepsi_maps_url')->nullable()->after('resepsi_time');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
