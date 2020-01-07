<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
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
    protected $table = 'properties';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'street', 
        'street_number', 
        'house_number', 
        'city', 
        'can_show', 
        'boss_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'boss_id' => 'integer'
    ];
    
    /**
     * Get boss that owns the property.
     */
    public function boss()
    {
        return $this->belongsTo('App\User', 'id');
    }
    
    
    
    /**
     * Get invoiceData which belongs to property.
     */
    public function invoiceData()
    {
        return $this->hasOne('App\InvoiceData');
    }
    
    /**
     * Get graphicRequests which belongs to property.
     */
    public function graphicRequests()
    {
        return $this->hasMany('App\GraphicRequest');
    }
    
    /**
     * Get years associated to property.
     */
    public function years()
    {
        return $this->hasMany('App\Year');
    }
}
