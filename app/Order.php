<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'price', 'execution_time', 'order', 'user_id', 'vendor_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'price' => 'decimal:2',
        'execution_time' => 'integer',
        'order' => 'integer',
        'user_id' => 'integer',
        'vendor_id' => 'integer'
    ];
    
    /**
     * Get the user which owns the order.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get the vendor which owns the order.
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
    
    /**
     * Get the order items which belong to order.
     */
    public function orderItems()
    {
        return $this->hasMany('App\OrderItem');
    }
}
