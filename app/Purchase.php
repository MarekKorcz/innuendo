<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{    
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'available_units', 'month_id', 'subscription_id', 'user_id', 'temp_user_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'available_units' => 'integer',
        'month_id' => 'integer',
        'subscription_id' => 'integer',
        'user_id' => 'integer',
        'temp_user_id' => 'integer'
    ];
    
    /**
     * Get appointments which belong to purchase.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get the month that owns the purchase.
     */
    public function month()
    {
        return $this->belongsTo('App\Month');
    }
    
    /**
     * Get the subscription that owns the purchase.
     */
    public function subscription()
    {
        return $this->belongsTo('App\Subscription');
    }
    
    /**
     * Get the user that owns the purchase.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get the temporary user that owns the purchase.
     */
    public function tempUser()
    {
        return $this->belongsTo('App\TempUser');
    }
}