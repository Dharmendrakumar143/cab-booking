<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDues extends Model
{
    protected $fillable = [
        'driver_id',
        'ride_id',
        'total_due',
        'last_payment',
        'due_date',
        'status'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class,'driver_id','id');
    }
}
