<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interval extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'available_units', 'used_units', 'start_date', 'end_date', 'interval_id', 'substart_id', 'purchase_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'available_units' => 'integer',
        'used_units' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'interval_id' => 'integer',
        'substart_id' => 'integer',
        'purchase_id' => 'integer'
    ];
    
    /**
     * Get the purchase that owns the interval.
     */
    public function substart()
    {
        return $this->belongsTo('App\Substart');
    }
    
    /**
     * Get the purchase that owns the interval.
     */
    public function purchase()
    {
        return $this->belongsTo('App\Purchase');
    }
    
    /**
     * Get appointments which belong to interval.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}