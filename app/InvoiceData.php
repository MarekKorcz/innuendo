<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceData extends Model
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
    protected $table = 'invoice_datas';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'address', 'email', 'phone_number', 'nip', 'bank_name', 'account_number', 'property_id', 'owner_id' 
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'property_id' => 'integer',
        'owner_id' => 'integer'
    ];
    
    /**
     * Get owner associated to invoiceData.
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }
    
    /**
     * Get property associated to invoiceData.
     */
    public function property()
    {
        return $this->belongsTo('App\Property');
    }
}