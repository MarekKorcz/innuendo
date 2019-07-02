<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChosenProperty extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chosen_properties';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id', 'user_id', 'property_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'code_id' => 'integer',
        'user_id' => 'integer',
        'property_id' => 'integer'
    ];
    
    /**
     * Get code associated to chosenProperty.
     */
    public function code()
    {
        return $this->belongsTo('App\Code');
    }
    
    /**
     * Get user associated to chosenProperty.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get property which belongs to ChosenProperty.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get purchases which belongs to ChosenProperty.
     */
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
    
    /**
     * Get subscriptions which belongs to ChosenProperty.
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription', 'chosen_property_subscription');
    }
}