<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'messages';                    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'topic', 
        'email', 
        'text', 
        'status'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id', 
        'graphic_request_id', 
        'promo_code_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'graphic_request_id' => 'integer',
        'promo_code_id' => 'integer'
    ];
    
    /**
     * Get user that owns message.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get graphicRequest that owns message.
     */
    public function graphicRequest()
    {
        return $this->belongsTo('App\GraphicRequest');
    }
    
    /**
     * Get promoCode that owns message.
     */
    public function promoCode()
    {
        return $this->belongsTo('App\PromoCode');
    }
}