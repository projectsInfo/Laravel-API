<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image','name', 'email', 'password','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function products()
{
    return $this->hasMany(Product::class);

}

    public function vegetables()
    {
        return $this->hasMany(vegetables::class);
    }

    public function fruits()
    {
        return $this->hasMany(fruits::class);
    }

    public function cart()
    {
        return $this->hasMany(cart::class);
    }

    public function wish()
    {
        return $this->hasMany(wish::class);
    }



}
