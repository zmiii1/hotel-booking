<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_add_ons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('normal_price', 10, 2)->nullable();
            $table->string('category');
            $table->string('image')->nullable();
            $table->boolean('is_prepayment_required')->default(false);
            $table->string('for_guests_type')->default('all'); // all, adult, child, specific
            $table->integer('guest_count')->nullable(); // For specific guest count
            $table->boolean('is_included')->default(false);
            $table->boolean('status')->default(true);
            $table->string('price_type')->default('one_time'); // per_night, one_time, per_person, per_hour
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_sale')->default(false);
            $table->timestamps();
        });
        
        Schema::create('booking_room_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_add_ons_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
        
        Schema::create('package_room_add_ons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_package_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_add_ons_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_room_add_ons');
        Schema::dropIfExists('package_room_add_ons');
        Schema::dropIfExists('room_add_ons');
    }
};