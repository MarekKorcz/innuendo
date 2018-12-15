<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'price', 'order_id', 'vendor_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
        'order_id' => 'integer',
        'vendor_id' => 'integer'
    ];
    
    /**
     * Get the order that owns the auction.
     */
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    
    /**
     * Get the vendor that owns the auction.
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
}
