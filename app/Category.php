<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{

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
    protected $fillable = ['name','parent_id','is_free'];

    public function children()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id','id');
    }

    public function getNameLabelAttribute()
    {
        return ucwords(strtolower($this->name) );
    }
}
