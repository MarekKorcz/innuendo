<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
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
        'name', 'slug', 'description', 'image', 'property_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'property_id' => 'integer'
    ];
    
    /**
     * Get the property that owns the category.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get the items which belong to category.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
