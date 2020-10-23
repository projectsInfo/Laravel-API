<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    protected $fillable = [
        'name','details', 'price','image','rate'
    ];
}
