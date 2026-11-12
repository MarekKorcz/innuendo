<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use SoftDeletes, HasFactory;

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
        'can_show'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
        return $this->belongsTo('User');
    }



    /**
     * Get invoiceData which belongs to property.
     */
    public function invoiceData()
    {
        return $this->hasOne('InvoiceData');
    }

    /**
     * Get invoices which belongs to property.
     */
    public function invoices()
    {
        return $this->hasMany('Invoice');
    }

    /**
     * Get graphicRequests which belongs to property.
     */
    public function graphicRequests()
    {
        return $this->hasMany('GraphicRequest');
    }

    /**
     * Get years associated to property.
     */
    public function years()
    {
        return $this->hasMany('Year');
    }

    /**
     * Get graphics associated to property.
     */
    public function graphics()
    {
        return $this->hasMany('Graphic');
    }
}
