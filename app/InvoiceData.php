<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceData extends Model
{
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
        'website', 'email', 'nip', 'krs', 'bank_name', 'account_number', 'swift', 'owner_id' 
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'owner_id' => 'integer'
    ];
    
    /**
     * Get owner associated to invoiceData.
     */
    public function owner()
    {
        return $this->belongsTo('App\User');
    }
}