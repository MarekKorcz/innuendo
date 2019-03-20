<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description', 'phone_number', 'street', 'street_number', 
        'house_number', 'city', 'isPublic', 'boss_id'
    ];
    
    /**
     *      ADMIN
     */
    
    /**
     * Get the calendar which belong to property
     */
    public function calendar()
    {
        return $this->hasOne('App\Calendar');
    }
    
    /**
     * Get subscriptions which belongs to property
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription');
    }
    
    /**
     *      BOSS
     */
    
    /**
     * Get boss that owns the property.
     */
    public function boss()
    {
        $boss = User::where('id', $this->boss_id)->first();
        
        return count($boss) > 0 ? $boss : null;
    }
    
    /**
     *      USER
     */
    /**
     * Get users which belongs to property
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
