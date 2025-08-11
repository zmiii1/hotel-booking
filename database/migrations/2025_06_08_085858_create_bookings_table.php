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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_id')->nullable();
            $table->integer('rooms_id')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->string('adults')->default(1);
            $table->string('child')->default(0);


            $table->float('total_night')->default(0);
            $table->float('actual_price')->default(0);
            $table->float('subtotal')->default(0);
            $table->integer('discount')->default(0);
            $table->float('total_amount')->default(0);

            $table->unsignedBigInteger('package_id')->nullable()->after('rooms_id');
            $table->unsignedBigInteger('promo_code_id')->nullable();
            $table->decimal('package_price', 10, 2);
            $table->decimal('addon_total', 15, 2);


            $table->string('payment_method')->default(0);
            $table->string('transaction_id')->default(0);
            $table->string('payment_status')->default(0);

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('additional_request')->nullable();
            $table->dropColumn('consent_marketing');
            
            $table->string('code')->unique();
            $table->string('status')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        
    }
};
