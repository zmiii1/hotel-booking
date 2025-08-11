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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_type_id');
            $table->text('description')->nullable();
            $table->string('room_capacity')->nullable();
            $table->integer('guests_total')->nullable();
            $table->string('image')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('size')->nullable();
            $table->string('bed_type')->nullable();
            $table->integer('discount')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('room_type_id')
            ->references('id')
            ->on('room_types')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
