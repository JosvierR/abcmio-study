<?php

namespace App\Http\Controllers\Api;

use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getImageFromPropertyByOwner(Property  $property)
    {
         if(auth()->check() && auth()->user()->id === $property->user_id) {
             $images = [];
             foreach($property->getMedia('gallery') as $media) {
                 $images[] = [
                       "id" => $media->id,
                       "size" => $media->size,
                       "url" => $media->getUrl(),
                       "thumb" => $media->getUrl('thumb'),
                 ];
             }
             return response()->json(['success' => true,  'result' => $images ], Response::HTTP_OK);
         }
         return response()->json(['success' => true,  'result' => $property], Response::HTTP_UNAUTHORIZED);
    }
}
