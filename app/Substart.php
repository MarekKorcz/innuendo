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
        'start_date', 'end_date', 'property_id', 'subscription_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'property_id' => 'integer',
        'subscription_id' => 'integer'
    ];
    
    /**
     * Get the property that owns subscription start.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get the subscription that owns subscription start.
     */
    public function subscription()
    {
        return $this->belongsTo('App\Property');
    }
}