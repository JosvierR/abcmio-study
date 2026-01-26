<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['slug','name','status'];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
