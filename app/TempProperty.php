<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempProperty extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temp_properties';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'street', 'street_number', 
        'house_number', 'city', 'boss_id'
    ];
    
    /**
     * Get temporary user which owns property.
     */
    public function tempUser()
    {
        return $this->belongsTo('App\TempUser');
    }
    
    /**
     * Get subscriptions which belongs to temporary property
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription', 'temp_property_subscription');
    }
}
