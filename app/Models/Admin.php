<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $table = "admins";
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'gender',
        'email',
        'email_verified_at',
        'password',
        'phone_number',
        'status',
        'profile_pic',
        'is_email_verified',
        'car_model',
        'car_number_plate',
        'driver_status',
        'verification_status',
        'google_id',
        'google_email',
        'is_2fa_enabled',
        'google_2fa_secret',
        'google_2fa_backup_codes',
        'stripe_account_id',
        'login_check',
        'auto_reject_ride'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function booking()
    {
        return $this->hasOne(Booking::class,'driver_id','id');
    }

    public function bookingMore()
    {
        return $this->hasMany(Booking::class,'driver_id','id');
    }

    public function driverDocuments()
    {
        return $this->hasOne(DriverDocuments::class,'driver_id','id');
    }
}
