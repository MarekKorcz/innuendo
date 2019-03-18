<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempUser extends Model
{
    use SoftDeletes;

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