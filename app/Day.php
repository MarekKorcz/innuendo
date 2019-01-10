<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
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
        'day_number', 'month_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'day_number' => 'integer',
        'month_id' => 'integer'
    ];
    
    /**
     * Get month that owns day.
     */
    public function month()
    {
        return $this->belongsTo('App\Month');
    }
    
    /**
     * Get appointment that is set to day
     */
    public function appointment()
    {
        return $this->hasOne('App\Appointment');
    }
}
