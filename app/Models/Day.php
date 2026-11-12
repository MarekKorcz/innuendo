<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'days';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'day_number',
        'number_in_week'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'month_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'day_number' => 'integer',
        'number_in_week' => 'integer',
        'month_id' => 'integer'
    ];

    /**
     * Get month that owns day.
     */
    public function month()
    {
        return $this->belongsTo('Month');
    }



    /**
     * Get appointments that is set to day
     */
    public function appointments()
    {
        return $this->hasMany('Appointment');
    }

    /**
     * Get graphic that belongs to day.
     */
    public function graphic()
    {
        return $this->hasOne('Graphic');
    }

    /**
     * Get graphicRequests which belongs to day.
     */
    public function graphicRequests()
    {
        return $this->hasMany('GraphicRequest');
    }
}
