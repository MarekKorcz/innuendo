<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
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
        'year', 'calendar_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'year' => 'integer',
        'calendar_id' => 'integer'
    ];
    
    /**
     * Get calendar that owns year.
     */
    public function calendar()
    {
        return $this->belongsTo('App\Calendar');
    }
    
    /**
     * Get months associated with year.
     */
    public function months()
    {
        return $this->hasMany('App\Month');
    }
}
