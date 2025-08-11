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
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beach_ticket_id')->constrained('beach_ticket');
            $table->string('order_code')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->date('visit_date');
            $table->integer('quantity');
            $table->text('additional_request')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, expired, cancelled
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_orders');
    }
};
