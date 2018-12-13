<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'image', 'vendor_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'vendor_id' => 'integer'
    ];
    
    /**
     * Get the vendor that owns the category.
     */
    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }
    
    /**
     * Get the items which belong to category.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}