<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'manufacture_time', 'image', 'category_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'manufacture_time' => 'integer',
        'category_id' => 'integer'
    ];
    
    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }
    
    /**
     * Get the order items which belong to item.
     */
    public function orderItems()
    {
        return $this->hasMany('App\OrderItem');
    }
}
