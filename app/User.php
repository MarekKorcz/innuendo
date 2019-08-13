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
        'name', 'slug', 'surname', 'phone_number', 'email', 'profile_image', 'password', 'isEmployee', 'isBoss', 'boss_id', 'isApproved'
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
     *      BOSS & ADMIN
     */
    
    /**
     * Get invoiceData which belongs to boss or admin.
     */
    public function invoiceDatas()
    {
        return $this->hasMany('App\InvoiceData');
    }
    
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
    public function getPlaces()
    {
        $properties = Property::where('boss_id', $this->id)->get();
        
        return count($properties) > 0 ? $properties : null;
    }
    
    /**
     * Get codes associated with boss.
     */
    public function codes()
    {
        return $this->hasMany('App\Code');
    }
    
    /**
     * Get GraphicRequests which has signed up employees to.
     */
    public function graphicRequests()
    {
        return $this->belongsToMany('App\GraphicRequest', 'graphic_request_employee', 'employee_id', 'graphic_request_id');
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
     * Get appointments associated with user.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get chosenProperties which belongs to user.
     */
    public function chosenProperties()
    {
        return $this->hasMany('App\ChosenProperty');
    }
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * Get calendars assigned to employee.
     */
    public function calendars()
    {
        return $this->hasMany('App\Calendar', 'employee_id');
    }
    
    public function getImageAttribute()
    {
        return $this->profile_image;
    }
}