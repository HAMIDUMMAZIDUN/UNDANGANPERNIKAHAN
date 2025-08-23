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
            // Kolom untuk Mempelai Pria
            $table->string('groom_name')->nullable()->after('name');
            $table->string('groom_photo')->nullable()->after('groom_name');
            $table->string('groom_parents')->nullable()->after('groom_photo');
            $table->string('groom_instagram')->nullable()->after('groom_parents');

            // Kolom untuk Mempelai Wanita
            $table->string('bride_name')->nullable()->after('groom_instagram');
            $table->string('bride_photo')->nullable()->after('bride_name');
            $table->string('bride_parents')->nullable()->after('bride_photo');
            $table->string('bride_instagram')->nullable()->after('bride_parents');

            // Kolom untuk konten tambahan
            $table->text('quran_verse')->nullable()->after('bride_instagram');
            $table->text('love_story')->nullable()->after('quran_verse');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'groom_name', 'groom_photo', 'groom_parents', 'groom_instagram',
                'bride_name', 'bride_photo', 'bride_parents', 'bride_instagram',
                'quran_verse', 'love_story'
            ]);
        });
    }
};
