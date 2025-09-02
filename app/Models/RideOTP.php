<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideOTP extends Model
{
    protected $table = "ride_otps";

    protected $fillable = [
        'ride_id',
        'otp',
        'otp_expiry'
    ];

}
