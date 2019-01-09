<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'place_id' => 'integer'
    ];
    
    /**
     * Get place that owns calendar.
     */
    public function place()
    {
        return $this->belongsTo('App\Place');
    }
    
    /**
     * Get years associated with calendar.
     */
    public function years()
    {
        return $this->hasMany('App\Year');
    }
}
