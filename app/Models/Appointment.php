<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time',
        'end_time',
        'minutes',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'graphic_id',
        'day_id',
        'item_id',
        'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'time',
        'end_time' => 'time',
        'minutes' => 'integer',
        'status' => 'integer',
        'graphic_id' => 'integer',
        'day_id' => 'integer',
        'item_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Get graphic that owns the appointment.
     */
    public function graphic()
    {
        return $this->belongsTo('Graphic');
    }

    /**
     * Get day that owns the appointment.
     */
    public function day()
    {
        return $this->belongsTo('Day');
    }

    /**
     * Get item that owns the appointment.
     */
    public function item()
    {
        return $this->belongsTo('Item');
    }

    /**
     * Get user that owns appointment.
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}
