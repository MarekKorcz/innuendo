<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Graphic extends Model
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
        'start_time', 'end_time', 'total_time', 'day_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'start_time' => 'time',
        'end_time' => 'time',
        'total_time' => 'integer',
        'day_id' => 'integer'
    ];
    
    /**
     * Get day that owns graphic.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
    
    /**
     * Get appointments assigned to employee.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}
