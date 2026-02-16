<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'role',
        'role_group',
        'assigned_services',

        'email',
        'phone',
        'date_hired',
        'status',

        'date_of_birth',
        'age',
        'place_of_birth',
        'gender',
        'citizenship',
        'religion',
        'civil_status',

        'country',
        'state_province',
        'city_municipality',
        'zipcode',
        'barangay',
        'complete_address',

        'highest_educational_attainment',
        'church_assigned',
        'formation',
        'date_of_ordination',
        'years_in_ministry',

        'license_no',
        'license_expiration',
        'ordination_deacons',
        'ordination_canonical_form',
    ];
}