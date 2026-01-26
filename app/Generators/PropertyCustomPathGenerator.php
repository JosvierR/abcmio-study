<?php


namespace App\Generators;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\PathGenerator\PathGenerator;

class PropertyCustomPathGenerator implements PathGenerator
{

    public function getPath(Media $media): string
    {
        // TODO: Implement getPath() method.
        $ID = \Auth::user()->id;
        return "/users/$ID/properties/{$media->id}/";
//        return md5($media->id).'/';
    }

    public function getPathForConversions(Media $media): string
    {
        // TODO: Implement getPathForConversions() method.
        return $this->getPath($media).'c/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        // TODO: Implement getPathForResponsiveImages() method.
        return $this->getPath($media).'/cri/';

    }
}
