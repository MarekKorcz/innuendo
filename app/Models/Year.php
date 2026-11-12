<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'years';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'property_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'year' => 'integer',
        'property_id' => 'integer'
    ];

    /**
     * Get property that owns year.
     */
    public function property()
    {
        return $this->belongsTo('Property');
    }



    /**
     * Get months associated with year.
     */
    public function months()
    {
        return $this->hasMany('Month');
    }
}
