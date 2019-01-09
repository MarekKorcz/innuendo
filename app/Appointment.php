<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time', 'minutes', 'item_id', 'day_id', 'user_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'start_time' => 'time',
        'minutes' => 'integer',
        'item_id' => 'integer',
        'day_id' => 'integer',
        'user_id' => 'integer'
    ];
    
    /**
     * Get item that owns the appointment.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
    
    /**
     * Get day that owns the appointment.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
    
    /**
     * Get user that owns appointment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
