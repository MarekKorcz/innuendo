<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'email', 'phone_number', 'street', 'street_number', 
        'house_number', 'city', 'post_code', 'country', 'user_id'
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
     * Get the user that owns the vendor.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get the categories which belong to vendor.
     */
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
    
    /**
     * Get the orders which belong to vendor.
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
}
