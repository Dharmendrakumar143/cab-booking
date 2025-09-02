<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
   protected $fillable = [
    'driver_id',
    'user_id',
    'payment_method_id',
    'brand',
    'last_four',
    'exp_month',
    'exp_year',
    'stripe_customer_id'
   ];

   public function booking()
   {
      return $this->hasOne(Booking::class,'user_card','id');
   }
   
}
