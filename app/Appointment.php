<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
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
        'start_time', 'start_time', 'minutes', 'employee_id', 'item_id', 'day_id', 'user_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'start_time' => 'time',
        'end_time' => 'time',
        'minutes' => 'integer',
        'employee_id' => 'integer',
        'day_id' => 'integer',
        'user_id' => 'integer',
        'item_id' => 'integer'
    ];
    
    /**
     *      ADMIN
     */
    
    /**
     * Get day that owns the appointment.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * Get employee that owns the appointment.
     */
    public function employee()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     *      USER
     */
    
    /**
     * Get user that owns appointment.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get item that owns the appointment.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
