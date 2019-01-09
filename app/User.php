<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'isAdmin'
    ];
    
    /**
     * Get places record associated with user.
     */
    public function places()
    {
        return $this->hasMany('App\Place');
    }
    
    /**
     * Get appointments associated with user.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}
