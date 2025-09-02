<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraCharges extends Model
{
    protected $fillable = [
        'name',
        'value',
        'is_weekend',
        'weekend_days',
        'is_holiday',
        'holiday_dates',
        'start_time',
        'end_time'
    ];
}
