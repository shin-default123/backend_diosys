<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $fillable = [
        'employee_id',
        'subject_updated',
        'full_name',
        'field',
        'old_value',
        'new_value',
        'edited_by',
        'time_stamp',
    ];

    protected $casts = [
        'time_stamp' => 'datetime',
    ];
}