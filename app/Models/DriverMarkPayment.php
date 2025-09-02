<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverMarkPayment extends Model
{
    protected $fillable = [
        'driver_id',
        'ride_id',
        'amount',
        'payment_method',
        'payment_date',
        'payment_time',
        'status',
        'ordering'
    ];
}
