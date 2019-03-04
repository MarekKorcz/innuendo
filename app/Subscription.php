<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
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
        'name', 'slug', 'description', 'old_price', 'new_price', 'quantity', 'duration'
    ];
    
    /**
     * Get properties which belongs to subscription
     */
    public function properties()
    {
        return $this->belongsToMany('App\Property');
    }
    
    /**
     * Get items which belongs to subscription
     */
    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
    
    /**
     * Get purchases which belong to subscription.
     */
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
}