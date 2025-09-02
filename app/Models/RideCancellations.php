<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideCancellations extends Model
{
    protected $fillable = [
        'customer_id',
        'driver_id',
        'ride_id',
        'cancel_id',
        'cancellation_reason'
    ];

    public function rideRequests()
    {
        return $this->belongsTo(RideRequests::class,'ride_id','id');
    }

    public function cancellationReasons()
    {
        return $this->belongsTo(CancellationReasons::class,'cancel_id','id');
    }
}
