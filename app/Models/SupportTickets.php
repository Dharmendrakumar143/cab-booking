<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTickets extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'issue_subject',
        'issue_description',
        'name',
        'email',
        'status',
        'ordering',
        'solutions'
    ];
}
