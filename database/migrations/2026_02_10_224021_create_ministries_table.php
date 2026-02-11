<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ministries', function (Blueprint $table) {
        $table->id();
        $table->string('name'); 
        $table->text('description')->nullable();
        $table->string('coordinator'); 
        $table->string('schedule')->nullable(); 
        $table->string('status')->default('Active'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ministries');
    }
};
