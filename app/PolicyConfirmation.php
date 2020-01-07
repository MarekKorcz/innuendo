<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolicyConfirmation extends Model
{       
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'policy_confirmations';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ip_address', 
        'confirm'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'confirm' => 'boolean',
    ];
}