<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Graphic extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'graphics';
    
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
        'start_time', 
        'end_time', 
        'total_time'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'day_id',
        'property_id',
        'employee_id'
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
        'day_id' => 'integer',
        'property_id' => 'integer',
        'employee_id' => 'integer'
    ];
    
    /**
     * Get day that owns graphic.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
    
    /**
     * Get property that owns graphic.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get employee that owns graphic.
     */
    public function employee()
    {
        return $this->belongsTo('App\User');
    }
    
    
    
    /**
     * Get appointments assigned to employee.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}
