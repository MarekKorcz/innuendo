<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
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
        'name', 'description', 'email', 'phone_number', 'street', 'street_number', 
        'house_number', 'city', 'postcode', 'country', 'user_id'
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
