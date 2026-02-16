<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('role_group')->nullable()->after('role'); 
            $table->string('assigned_services')->nullable()->after('role_group');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['role_group', 'assigned_services']);
            $table->dropSoftDeletes();
        });
    }
};