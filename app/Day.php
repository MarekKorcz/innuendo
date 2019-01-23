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
        'day_number', 'number_in_week', 'month_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'day_number' => 'integer',
        'number_in_week' => 'integer',
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
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get graphic that belongs to day.
     */
    public function graphic()
    {
        return $this->hasOne('App\Graphic');
    }
}
