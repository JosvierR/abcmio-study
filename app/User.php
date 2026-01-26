<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Inani\Messager\Helpers\MessageAccessible;
use Inani\Messager\Helpers\TagsCreator;

class User extends Authenticatable //implements MustVerifyEmail
{
    use Notifiable, MessageAccessible, TagsCreator;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'country_id',
        'birth_date',
        'gender',
        'confirmed',
        'token',
        'credits'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? \Hash::make($input) : $input;
        }
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function propertiesWithRelation()
    {
        return $this->hasMany(Property::class)->with(['media', 'country', 'category', 'user']);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

// Access
    public function getIsAdminAttribute()
    {
        return in_array($this->type, ["admin", "super"]);
    }

    public function isAdmin()
    {
        return in_array($this->type, ["admin", "super"]);
    }

    public function propertyTotal()
    {
        return $this->withCount('properties')->get();
    }

    /**
     * Accessors
     */

    public function getTotalCreditsAttribute()
    {
        return (int) $this->credits;
    }

}
