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
        Schema::create('baptisms', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('place');
                $table->string('name');
                $table->integer('age')->nullable();
                $table->string('sex'); 
                $table->string('legitimacy')->nullable(); 
                $table->string('father')->nullable();
                $table->string('mother')->nullable();
                $table->text('godparents')->nullable(); 
                $table->text('grandparents')->nullable();
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baptisms');
    }
};
