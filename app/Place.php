<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Place extends Model
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
        'name', 'slug', 'description', 'phone_number', 'street', 'street_number', 
        'house_number', 'city', 'user_id'
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
     * Get the categories which belong to place.
     */
    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
