<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'remember_token',
        'boss_id',
        'password',
        'isAdmin',
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
        'is_approved' => 'boolean',
        'isAdmin' => 'boolean',
        'isBoss' => 'boolean',
        'isEmployee' => 'boolean',
        'boss_id' => 'integer'
    ];

    /**
     *      COMMON
     */

    /**
     * Get appointments associated with entity.
     */
    public function appointments()
    {
        return $this->hasMany('Appointment');
    }

    /**
     * Get messages associated with entity.
     */
    public function messages()
    {
        return $this->hasMany('Message');
    }

    /**
     *      BOSS
     */

    /**
     * Get properties which belongs to boss.
     */
    public function properties()
    {
        return $this->hasMany('Property', 'boss_id');
    }

    /**
     * Get boss workers collection
     */
    public function getWorkers()
    {
        return $this->isBoss == 1 ? User::where('boss_id', $this->id)->get() : [];
    }

    /**
     * Get code assigned to boss.
     */
    public function code()
    {
        return $this->hasOne('Code', 'boss_id');
    }

    /**
     * Get promo code assigned to boss.
     */
    public function promoCode()
    {
        return $this->hasOne('PromoCode', 'boss_id');
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
     * Get boss properties assigned to worker
     */
    public function getBossProperties()
    {
        if ($this->isAdmin == 0 && $this->isBoss == 0 && $this->isEmployee == 0 && $this->boss_id !== null)
        {
            return Property::where([
                'boss_id' => $this->boss_id
            ])->get();
        }
    }

    /**
     *      EMPLOYEE
     */

    /**
     * Get graphics assigned to employee.
     */
    public function graphics()
    {
        return $this->hasMany('Graphic', 'employee_id');
    }

    /**
     * Get GraphicRequests which has signed up employees to.
     */
    public function graphicRequests()
    {
        return $this->belongsToMany('GraphicRequest', 'graphic_request_employee', 'employee_id', 'graphic_request_id');
    }

    public function getImageAttribute()
    {
        return $this->profile_image;
    }
}
