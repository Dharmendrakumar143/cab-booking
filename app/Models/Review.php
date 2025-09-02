<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'ride_id',
        'rating',
        'feedback',
        'status',
        'ordering'
    ];

    public function rideRequests()
    {
        return $this->belongsTo(RideRequests::class,'ride_id','id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
}
