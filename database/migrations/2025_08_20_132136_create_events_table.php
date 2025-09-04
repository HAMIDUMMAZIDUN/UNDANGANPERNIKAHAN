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
        Schema::create('events', function (Blueprint $table) {
            // Kolom Inti
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique(); 
            $table->date('date');
            $table->json('content')->nullable(); 
            $table->string('location')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('description')->nullable();
            $table->string('groom_name')->nullable();
            $table->string('groom_photo')->nullable();
            $table->string('groom_parents')->nullable();
            $table->string('groom_instagram')->nullable();
            $table->string('bride_name')->nullable();
            $table->string('bride_photo')->nullable();
            $table->string('bride_parents')->nullable();
            $table->string('bride_instagram')->nullable();
            $table->text('love_story')->nullable();
            $table->string('location_url')->nullable();
            $table->string('akad_location')->nullable();
            $table->string('akad_time')->nullable();
            $table->string('akad_maps_url')->nullable();
            $table->string('resepsi_location')->nullable();
            $table->string('resepsi_time')->nullable();
            $table->string('resepsi_maps_url')->nullable();
            $table->string('rekening_bank')->nullable();
            $table->string('rekening_atas_nama')->nullable();
            $table->string('rekening_nomor')->nullable();  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
