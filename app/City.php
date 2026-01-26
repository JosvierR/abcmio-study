<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable= ['slug','name','country_id'];

    use Sluggable;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
