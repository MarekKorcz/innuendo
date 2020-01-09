<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{    
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'promos';
    
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
        'title', 
        'title_en', 
        'description', 
        'description_en', 
        'available_code_count', 
        'used_code_count', 
        'total_code_count', 
        'is_active'
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
        'is_active' => 'boolean'
    ];
    
    
    
    /**
     * Get promo codes that belong to promo.
     */
    public function promoCodes()
    {
        return $this->hasMany('App\PromoCode');
    }
}