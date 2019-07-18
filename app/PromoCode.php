<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promo_codes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'activation_time', 'isActive', 'boss_id', 'promo_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'isActive' => 'boolean',
        'boss_id' => 'integer',
        'promo_id' => 'integer'
    ];
    
    /**
     * Get boss who owns this code.
     */
    public function boss()
    {
        return $this->hasOne('App\User');
    }
    
    /**
     * Get promo code associated to promo.
     */
    public function promo()
    {
        return $this->belongsTo('App\Promo');
    }
    
    /**
     * Get subscriptions which belongs to promo code.
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\Subscription', 'promo_code_subscription');
    }
    
    /**
     * Get messages which belongs to promoCode.
     */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}