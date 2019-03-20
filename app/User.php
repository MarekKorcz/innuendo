<?php

namespace App;

use Illuminate\Notifications\Notifiable;
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
        'name', 'surname', 'phone_number', 'email', 'password', 'code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'slug', 'password', 'remember_token', 'isAdmin', 'isEmployee', 'isBoss', 'boss_id'
    ];
    
    /**
     *      BOSS
     */
    
    public function getWorkers()
    {
        $workers = User::where('boss_id', $this->id)->get();
        
        return count($workers) > 0 ? $workers : null;
    }
    
    /**
     * Get properties which belong to boss.
     */
    public function places()
    {
        $properties = Property::where('boss_id', $this->id)->get();
        
        return count($properties) > 0 ? $properties : null;
    }

    /**
     *      USER
     */
    
    public function getBoss()
    {
        $boss = User::where('id', $this->boss_id)->first();
        
        return $boss !== null ? $boss : null;
    }
    
    /**
     * Get properties which belongs to user
     */
    public function properties()
    {
        return $this->belongsToMany('App\Property');
    }
    
    /**
     * Get appointments associated with user.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get purchases which belong to user.
     */
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * Get calendars assigned to employee.
     */
    public function calendars()
    {
        return $this->hasMany('App\Calendar');
    }
}