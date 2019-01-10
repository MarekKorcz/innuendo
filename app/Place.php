<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
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
        'name', 'slug', 'description', 'phone_number', 'street', 'street_number', 
        'house_number', 'city', 'owner_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'owner_id' => 'integer'
    ];
    
    /**
     *      ADMIN
     */
    
    /**
     * Get the owner that owns the place
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get the categories which belongs to place
     */
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
    
    /**
     * Get the calendars which belongs to place
     */
    public function calendars()
    {
        return $this->hasMany('App\Calendar');
    }
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * The employees that belongs to the place.
     */
    public function employees()
    {
        return $this->belongsToMany('App\User', 'place_employee', 'place_id', 'employee_id');
    }
}
