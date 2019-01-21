<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calendar extends Model
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
        'property_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'property_id' => 'integer'
    ];
    
    /**
     *      ADMIN
     */
    
    /**
     * Get property that owns calendar.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get years associated with calendar.
     */
    public function years()
    {
        return $this->hasMany('App\Year');
    }
}
