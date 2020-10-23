<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class wish extends Model
{
    protected $fillable = [
        'name','details', 'price','image'
    ];
}
