<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Photo;
use App\Property;
use http\Env\Response;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePhotoRequest $storePhotoRequest)
    {
        return $storePhotoRequest->all();
//        return $storePhotoRequest->file('photo_url');
//        if ($files = $storePhotoRequest->file('profileImage')) {
//            // Define upload path
//            $destinationPath = public_path('/profile_images/'); // upload path
//            foreach($files as $img) {
//                // Upload Orginal Image
//                $profileImage =$img->getClientOriginalName();
//                $img->move($destinationPath, $profileImage);
//                // Save In Database
//                $imagemodel= new Photo();
//                $imagemodel->photo_name="$profileImage";
//                $imagemodel->save();
//            }
//
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        $property = $photo->property;
        $photo->delete();
        $total = $property->photos->count();
        return response()->json(['success'=>true,'msg'=>'Foto borrada','total'=>$total]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update($locale, StorePhotoRequest $request, $id)
    {
        $property = Property::find($id);
        $property->addMedia($request->file)->toMediaCollection("gallery");
        return $property->getMedia("gallery")->last()->getUrl();

    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  \App\Photo  $photo
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id,Request $request)
//    {
//        return  $request->all();
//    }

    public function display(Property $property,$key)
    {
//        return $property->getMedia("gallery")->where("id",$key)->first()->delete();
        return $property->getMedia("gallery")->where("id",$key);
    }

    public function delete(Property $property, $key)
    {
        $user_id = \Auth::user()->id;
        if($user_id === $property->user_id)
        {
//            $key = (int)$request->key;
            $property->getMedia("gallery")
                ->where("id", $key)
                ->first()
                ->delete();
//            $media = $property->getMedia("gallery");
//            $media[(int)$key]->delete();
            return response()->json(['success'=>true,"total"=>count($property->getMedia("gallery"))],200);
        }
        return response()->json(['success'=>false],200);

    }
}
