<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TempUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'email', 'phone_number'
    ];
    
    /**
     *      USER
     */
    
    /**
     * Get appointments associated with user.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}