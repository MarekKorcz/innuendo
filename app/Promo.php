<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
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
        'title', 'title_en', 'description', 'description_en', 'available_code_count', 'used_code_count', 
        'total_code_count', 'isActive', 'admin_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'available_code_count' => 'integer',
        'used_code_count' => 'integer',
        'total_code_count' => 'integer',
        'isActive' => 'boolean',
        'admin_id' => 'integer'
    ];
    
    /**
     * Get promo codes that belong to promo.
     */
    public function promoCodes()
    {
        return $this->hasMany('App\PromoCode');
    }
}