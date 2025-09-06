<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->nullable()->after('status'); // Untuk menyimpan harga
            $table->string('payment_status')->default('unpaid')->after('price'); // unpaid, paid
            $table->string('order_id')->nullable()->unique()->after('payment_status'); // ID unik untuk transaksi
            $table->text('qris_url')->nullable()->after('order_id'); // URL gambar QRIS
            $table->integer('template_id')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('client_requests', function (Blueprint $table) {
            $table->dropColumn(['price', 'payment_status', 'order_id', 'qris_url']);
        });
    }
};