<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity', 'order_id', 'item_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'quantity' => 'integer',
        'order_id' => 'integer',
        'item_id' => 'integer'
    ];
    
    /**
     * Get the item which owns the order item.
     */
    public function item()
    {
        return $this->belongsTo('App\Item');
    }
    
    /**
     * Get the order which owns the order item.
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
