<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
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
        'invoice'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'property_id',
        'month_id',
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'property_id' => 'integer',
        'month_id' => 'integer'
    ];
    
    /**
     * Get property associated to invoice.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
    
    /**
     * Get month associated to invoice.
     */
    public function month()
    {
        return $this->belongsTo('App\Month');
    }
}