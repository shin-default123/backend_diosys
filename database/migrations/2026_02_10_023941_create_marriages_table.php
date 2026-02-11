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
       Schema::create('marriages', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('place');
            $table->string('husband_name');
            $table->string('husband_father')->nullable();
            $table->string('husband_mother')->nullable(); 
            $table->string('husband_grandparents')->nullable(); 

            $table->string('wife_name');
            $table->string('wife_father')->nullable(); 
            $table->string('wife_mother')->nullable(); 
            $table->string('wife_grandparents')->nullable(); 
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
