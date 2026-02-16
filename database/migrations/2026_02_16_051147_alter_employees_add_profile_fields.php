<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Basic identity
            $table->string('middle_name')->nullable()->after('first_name');

            // Demographics
            $table->date('date_of_birth')->nullable()->after('date_hired');
            $table->unsignedInteger('age')->nullable()->after('date_of_birth');
            $table->string('place_of_birth')->nullable()->after('age');
            $table->string('gender')->nullable()->after('place_of_birth');
            $table->string('citizenship')->nullable()->after('gender');
            $table->string('religion')->nullable()->after('citizenship');
            $table->string('civil_status')->nullable()->after('religion');

            // Address
            $table->string('country')->nullable()->after('civil_status');
            $table->string('state_province')->nullable()->after('country');
            $table->string('city_municipality')->nullable()->after('state_province');
            $table->string('zipcode')->nullable()->after('city_municipality');
            $table->string('barangay')->nullable()->after('zipcode');
            $table->string('complete_address')->nullable()->after('barangay');

            // Church-related / formation
            $table->string('highest_educational_attainment')->nullable()->after('complete_address');
            $table->string('church_assigned')->nullable()->after('highest_educational_attainment');
            $table->string('formation')->nullable()->after('church_assigned');
            $table->date('date_of_ordination')->nullable()->after('formation');
            $table->unsignedInteger('years_in_ministry')->nullable()->after('date_of_ordination');

            // Licenses / ordinations
            $table->string('license_no')->nullable()->after('years_in_ministry');
            $table->date('license_expiration')->nullable()->after('license_no');
            $table->string('ordination_deacons')->nullable()->after('license_expiration');
            $table->string('ordination_canonical_form')->nullable()->after('ordination_deacons');
        });
    }

    public function down(): void
    {
    }
};