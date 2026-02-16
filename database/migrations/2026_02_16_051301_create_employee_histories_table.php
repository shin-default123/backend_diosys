<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employee_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->index();
            $table->string('subject_updated')->nullable(); 
            $table->string('full_name')->nullable();       

            $table->string('field');                       
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();

            $table->string('edited_by')->nullable();     
            $table->timestamp('time_stamp')->useCurrent();

            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_histories');
    }
};