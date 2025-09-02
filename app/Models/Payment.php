<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'ride_id',
        'driver_id',
        'amount',
        'payment_method',
        'payment_date',
        'payment_time',
        'status',
        'ordering'
    ];
}
