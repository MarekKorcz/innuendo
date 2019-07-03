<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GraphicRequest extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'graphic_requests';                    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time', 'end_time', 'comment', 'property_id', 'year_id', 'month_id', 'day_id', 'boss_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'property_id' => 'integer',
        'year_id' => 'integer',
        'day_id' => 'integer',
        'boss_id' => 'integer'
    ];
    
    /**
     * Get property that owns GraphicRequest.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get year that owns GraphicRequest.
     */
    public function year()
    {
        return $this->belongsTo('App\Year');
    }
    
    /**
     * Get month that owns GraphicRequest.
     */
    public function month()
    {
        return $this->belongsTo('App\Month');
    }
    
    /**
     * Get day that owns GraphicRequest.
     */
    public function day()
    {
        return $this->belongsTo('App\Day');
    }
    
    /**
     * Get employees which belongs to GraphicRequest.
     */
    public function employees()
    {
        return $this->belongsToMany('App\User', 'graphic_request_employee', 'graphic_request_id', 'employee_id');
    }
    
    /**
     * Get messages which belongs to GraphicRequest.
     */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}