<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipAmount extends Model
{
    protected $fillable = [
        'driver_id',
        'customer_id',
        'ride_id',
        'total_tip_amount',
    ];
}
