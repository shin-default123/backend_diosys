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
            
            // Event Details
            $table->string('type');  // Matrimony, Baptism, etc.
            $table->date('date');
            $table->string('time');  // e.g., "10:00 AM"
            $table->string('venue');

            // Personal Details
            $table->string('name');
            $table->string('email')->nullable(); // Optional
            $table->string('phone');

            // Status (Pending, Approved, Rejected)
            $table->string('status')->default('Pending'); 

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