<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'slug',
        'type',
        'publish',
        'status',
        'ordering'
    ];

    public function PageContent(){
        return $this->hasOne(PageContent::class,'page_id','id');
    }
}
