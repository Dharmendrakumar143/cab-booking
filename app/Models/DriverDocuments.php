<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocuments extends Model
{
    protected $fillable = [
        'driver_id',
        'verify_by',
        'license',
        'registration',
        'insurance',
        'ownership_tesla_model',
        'driver_rating',
        'verification_status',
        'message'
    ];
}
