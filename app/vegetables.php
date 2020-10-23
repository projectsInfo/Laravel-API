<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vegetables extends Model
{
    protected $fillable = [
        'name', 'details', 'price', 'image'
    ];
}
