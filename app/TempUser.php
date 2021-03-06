<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TempUser extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temp_users';

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
        'name', 
        'surname', 
        'email', 
        'phone_number', 
        'register_code'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'isBoss',
        'isEmployee'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'phone_number' => 'integer',
        'isBoss' => 'boolean',
        'isEmployee' => 'boolean'
    ];
    
    
    
    /**
     * Get temporary property associated with temporary user.
     */
    public function tempProperty()
    {
        return $this->hasOne('App\TempProperty');
    }
}