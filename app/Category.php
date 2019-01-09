<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
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
        'name', 'slug', 'description', 'image', 'place_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'place_id' => 'integer'
    ];
    
    /**
     * Get the place that owns the category.
     */
    public function place()
    {
        return $this->belongsTo('App\Place');
    }
    
    /**
     * Get the items which belong to category.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }
}
