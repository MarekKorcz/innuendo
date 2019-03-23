<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Authenticatable
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
        'code', 'boss_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'boss_id' => 'integer'
    ];
    
    /**
     * Get boss that owns the code.
     */
    public function boss()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get chosenProperties which belongs to code.
     */
    public function chosenProperties()
    {
        return $this->hasMany('App\ChosenProperty');
    }
}