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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->index();
            $table->string('payment_id')->nullable(); // Xendit payment ID
            $table->string('external_id')->unique(); // ID yang digunakan untuk Xendit
            $table->string('payment_method')->nullable();
            $table->string('channel_code')->nullable();
            $table->string('payment_status');
            $table->decimal('amount', 12, 2);
            $table->string('checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
            
            $table->foreign('booking_code')->references('code')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
