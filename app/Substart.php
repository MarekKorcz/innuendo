<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Substart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date', 'user_id', 'boss_id', 'property_id', 'subscription_id', 'purchase_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'user_id' => 'integer',
        'boss_id' => 'integer',
        'isActive' => 'boolean',
        'property_id' => 'integer',
        'subscription_id' => 'integer',
        'purchase_id' => 'integer'
    ];
    
    /**
     * Get intervals which belong to purchase.
     */
    public function intervals()
    {
        return $this->hasMany('App\Interval');
    }
    
    /**
     * Get the purchase that owns subscription start.
     */
    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }
}