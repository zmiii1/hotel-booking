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
        Schema::create('booking_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('room_package_id');
            $table->decimal('package_price', 10, 2)->default(0);
            $table->timestamps();
            
            $table->foreign('booking_id')
                ->references('id')
                ->on('bookings')
                ->onDelete('cascade');
                
            $table->foreign('room_package_id')
                ->references('id')
                ->on('room_packages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_packages');
    }
};
