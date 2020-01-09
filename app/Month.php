<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Month extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'months';
    
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
        'month', 
        'month_en', 
        'month_number', 
        'days_in_month'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'year_id'
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
}
