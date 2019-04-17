<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
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
        'subscription_id',
        'chosen_property_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'subscription_id' => 'integer',
        'chosen_property_id' => 'integer'
    ];
    
    /**
     * Get intervals which belong to purchase.
     */
    public function intervals()
    {
        return $this->hasMany('App\Interval');
    }
    
    /**
     * Get appointments which belong to purchase.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get the subscription that owns the purchase.
     */
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }
    
    /**
     * Get the chosenProperty that owns the purchase.
     */
    public function chosenProperty()
    {
        return $this->belongsTo('App\ChosenProperty');
    }
    
    /**
     * Get subscription start that belongs to purchase.
     */
    public function substart()
    {
        return $this->hasOne('App\Substart');
    }
}