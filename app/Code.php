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
        'code', 'user_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer'
    ];
    
    
    /**
     * Get properties which belongs to code
     */
    public function properties()
    {
        return $this->belongsToMany('App\Property');
    }
    
    /**
     * Get boss that owns the code.
     */
    public function boss()
    {
        return $this->belongsTo('App\User');
    }
}