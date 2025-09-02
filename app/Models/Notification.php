<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'message',
        'notification_type',
        'read_status',
        'ordering',
        'title',
        'type',
        'read_at'
    ];
}
