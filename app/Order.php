<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'execution_time', 'order', 'user_id', 'vendor_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'execution_time' => 'time',
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
