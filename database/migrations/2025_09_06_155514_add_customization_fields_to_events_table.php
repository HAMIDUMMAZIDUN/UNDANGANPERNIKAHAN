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
            // Menambahkan semua kolom baru setelah kolom yang sudah pasti ada
            $table->string('template_name')->nullable()->default('classic-gold')->after('resepsi_maps_url');

            $table->string('gift_bank_1_name')->nullable()->after('template_name');
            $table->string('gift_bank_1_account')->nullable()->after('gift_bank_1_name');
            $table->string('gift_bank_1_holder')->nullable()->after('gift_bank_1_account');

            $table->string('gift_bank_2_name')->nullable()->after('gift_bank_1_holder');
            $table->string('gift_bank_2_account')->nullable()->after('gift_bank_2_name');
            $table->string('gift_bank_2_holder')->nullable()->after('gift_bank_2_account');

            $table->text('gift_address')->nullable()->after('gift_bank_2_holder');

            $table->date('story_1_date')->nullable()->after('gift_address');
            $table->string('story_1_title')->nullable()->after('story_1_date');
            $table->text('story_1_description')->nullable()->after('story_1_title');
            $table->string('story_1_image')->nullable()->after('story_1_description');
            
            $table->date('story_2_date')->nullable()->after('story_1_image');
            $table->string('story_2_title')->nullable()->after('story_2_date');
            $table->text('story_2_description')->nullable()->after('story_2_title');
            $table->string('story_2_image')->nullable()->after('story_2_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Hapus semua kolom jika migrasi di-rollback
            $table->dropColumn([
                'template_name',
                'gift_bank_1_name', 'gift_bank_1_account', 'gift_bank_1_holder',
                'gift_bank_2_name', 'gift_bank_2_account', 'gift_bank_2_holder',
                'gift_address',
                'story_1_date', 'story_1_title', 'story_1_description', 'story_1_image',
                'story_2_date', 'story_2_title', 'story_2_description', 'story_2_image',
            ]);
        });
    }
};
