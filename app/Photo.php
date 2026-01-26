<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\File;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;


class Photo extends Model implements HasMedia
{
    use HasMediaTrait;
    protected $fillable = ['property_id','photo_url'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function registerMediaCollections()
    {
        $this->addMediaCollection('thumb')
            ->width(300)
            ->height(300)
            ->singleFile()
            ->withResponsiveImages()
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/jpeg';
            });
    }
}
