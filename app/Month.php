<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Month extends Model
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
        'month', 'month_number', 'days_in_month', 'year_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'month_number' => 'integer',
        'days_in_month' => 'integer',
        'year_id' => 'integer'
    ];
    
    /**
     * Get year that owns month.
     */
    public function year()
    {
        return $this->belongsTo('App\Year');
    }
    
    /**
     * Get days associated with month.
     */
    public function days()
    {
        return $this->hasMany('App\Day');
    }
    
    /**
     * Get purchases which belong to month.
     */
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
}
