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
     * Get employees which belongs to GraphicRequest.
     */
    public function employees()
    {
        return $this->belongsToMany('App\User', 'graphic_request_employee', 'graphic_request_id', 'employee_id');
    }
}