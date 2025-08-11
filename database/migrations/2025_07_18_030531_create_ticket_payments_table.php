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
        Schema::create('ticket_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_order_id')->constrained()->onDelete('cascade');
            $table->string('order_code');
            $table->string('payment_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('payment_status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('checkout_url')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_payments');
    }
};
