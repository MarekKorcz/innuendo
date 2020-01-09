<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'surname', 
        'slug', 
        'phone_number', 
        'email',
        'profile_image',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token', 
        'isAdmin', 
        'isBoss', 
        'isEmployee',
        'boss_id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     * 
     * @var array
     */
    protected $casts = [
        'phone_number' => 'integer',
        'is_approved' => 'boolean',
        'isAdmin' => 'boolean',
        'isBoss' => 'boolean',
        'isEmployee' => 'boolean',
        'boss_id' => 'boolean'
    ];
    
    /**
     *      COMMON
     */
    
    /**
     * Get appointments associated with entity.
     */
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
    
    /**
     * Get messages associated with entity.
     */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }
    
    /**
     *      BOSS
     */
    
    /**
     * Get properties which belongs to boss.
     */
    public function properties()
    {
        return $this->hasMany('App\Property');
    }
    
    /**
     * Get boss workers collection
     */
    public function getWorkers()
    {
        $this->isBoss == 1 ? User::where('boss_id', $this->id)->get() : [];
    }
    
    /**
     * Get code assigned to boss.
     */
    public function code()
    {
        return $this->hasOne('App\Code');
    }
    
    /**
     * Get promo code assigned to boss.
     */
    public function promoCode()
    {
        return $this->hasOne('App\PromoCode');
    }

    /**
     *      WORKER
     */
    
    /**
     * Get boss assigned to worker
     */
    public function getBoss()
    {
        $boss = null;
                
        if ($this->isAdmin == 0 && $this->isBoss == 0 && $this->isEmployee == 0)
        {
            $boss = User::where('id', $this->boss_id)->first();
        }
        
        return $boss !== null ? $boss : null;
    }
    
    /**
     *      EMPLOYEE
     */
    
    /**
     * Get graphics assigned to employee.
     */
    public function graphics()
    {
        return $this->belongsToMany('App\Graphic', 'graphic_employee', 'employee_id', 'graphic_id');
    }
    
    /**
     * Get GraphicRequests which has signed up employees to.
     */
    public function graphicRequests()
    {
        return $this->belongsToMany('App\GraphicRequest', 'graphic_request_employee', 'employee_id', 'graphic_request_id');
    }
    
    public function getImageAttribute()
    {
        return $this->profile_image;
    }
}