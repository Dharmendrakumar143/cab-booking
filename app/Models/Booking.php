<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'driver_id',
        'ride_id',
        'booking_date',
        'booking_time',
        'payment_method',
        'booking_status',
        'status',
        'ride_booking_amount',
        'final_booking_amount',
        'distance',
        'duration',
        'miles_distance',
        'extend_distance',
        'extend_miles_distance',
        'user_card',
        'admin_commission',
        'driver_earning',
        'reject_by_super_admin',
        'is_otp_verified',
        'reject_by_employee',
        'user_phone_number',
        'booking_number'
    ];
    

    public function rideRequests()
    {
        return $this->belongsTo(RideRequests::class,'ride_id','id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class,'driver_id','id');
    }

    public function card()
    {
        return $this->belongsTo(Card::class,'user_card','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'customer_id','id');
    }
}
