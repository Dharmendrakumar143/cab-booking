<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RideRequests extends Model
{
    protected $fillable = [
        'customer_id',
        'pick_up_address',
        'pick_up_city',
        'pick_up_state',
        'pick_up_country',
        'pick_up_latitude',
        'pick_up_longitude',
        'pick_up_date',
        'pick_up_time',
        'drop_off_address',
        'drop_off_city',
        'drop_off_state',
        'drop_off_country',
        'drop_off_latitude',
        'drop_off_longitude',
        'drop_off_date',
        'drop_off_time',
        'ride_status',
        'total_passenger',
        'status',
        'ordering',
        'person_name',
        'phone_number'
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class,'ride_id','id');
    }


    public function cancelledRide()
    {
        return $this->hasOne(RideCancellations::class,'ride_id','id');
    }

    public function users()
    {
        return $this->belongsTo(User::class,'customer_id','id');
    }

    public function review()
    {
        return $this->hasOne(Review::class,'ride_id','id');
    }

    public function paymentStatus()
    {
        return $this->hasOne(Payment::class,'ride_id','id');
    }
}
