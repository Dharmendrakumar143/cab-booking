<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverOTP extends Model
{
    protected $table = "driver_otps";

    protected $fillable = [
        'driver_id',
        'otp',
        'otp_expiry'
    ];

}
