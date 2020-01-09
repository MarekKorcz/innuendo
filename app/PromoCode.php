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
        'code', 
        'activation_time', 
        'is_active'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'promo_id',
        'boss_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'promo_id' => 'integer',
        'boss_id' => 'integer'
    ];
    
    /**
     * Get promo code associated to promo.
     */
    public function promo()
    {
        return $this->belongsTo('App\Promo');
    }
    
    /**
     * Get boss who owns this code.
     */
    public function boss()
    {
        return $this->belongsTo('App\User');
    }
    
    
    
    /**
     * Get messages which belongs to promoCode.
     */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
}