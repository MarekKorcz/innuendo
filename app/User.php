<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
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
        'name', 'email', 'password', 'boss_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'isAdmin', 'isEmployee'
    ];
    
    /**
     *      ADMIN
     */
    
    /**
     * Get places record associated with user.
     */
    public function places()
    {
        return $this->hasMany('App\Place');
    }
    
    /**
     * Get employees associated to boss
     */
    public function employee()
    {
        return $this->hasMany('App\User', 'boss_id');
    }
    
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
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * Get boss assigned to employee.
     */
    public function boss()
    {
        return $this->belongsTo('App\User', 'boss_id');
    }
    
    /**
     * Get workplaces assigned to employee.
     */
    public function workplaces()
    {
        return $this->belongsToMany('App\Place', 'place_employee', 'employee_id', 'place_id');
    }
    
    /**
     * Get calendar assigned to employee.
     */
    public function calendars()
    {
        return $this->hasMany('App\Calendar');
    }
    
    /**
     * Get units of work assigned to employee.
     */
    public function unitsOfWork()
    {
        return $this->hasMany('App\Appointment');
    }
}
