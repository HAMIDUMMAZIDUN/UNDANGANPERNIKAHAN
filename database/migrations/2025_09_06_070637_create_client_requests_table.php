<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // e.g., "Undangan Classic Gold"
            $table->integer('template_id');
            $table->string('status')->default('pending'); // pending, waiting_for_payment, waiting_for_approval, approve, etc.
            $table->decimal('price', 15, 2)->nullable();
            $table->string('payment_status')->default('unpaid'); // unpaid, paid
            $table->string('order_id')->nullable()->unique();
            $table->text('qris_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_requests');
    }
};