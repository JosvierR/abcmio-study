<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Mail\UserCreate;
use App\Property;
use App\User;
use Illuminate\Http\Request;
use Mail;
use Carbon\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $properties  = Property::get();
        foreach($properties as $property)
        {
            if(!empty($property->image_path))
            {
                $image = \Storage::url($property->image_path);
                echo "<img src='$image'> <br/>";
            }
        }
//        Property::where("expire_date",'<>',null)
//            ->where("start_date","<=",Carbon::now())
//            ->where("expire_date","<",Carbon::now())
//            ->update(['is_public'=>false,"start_date"=>null,"expire_date"=>null]);
//
//        return PropertyResource::collection(
//            Property::where("expire_date",'<>',null)
//            ->where("start_date","<=",Carbon::now())
//            ->where("expire_date","<",Carbon::now())
//            ->get())
//            ;


//        $user = User::latest()->first();
//        Mail::to('ce.pichardo@gmail.com')->bcc('jrozon@ingenieriarozon.com')->send(new UserCreate($user));
//        $this->send_email();
//       $data['title'] = "This is Test Mail Tuts Make";
//        Mail::send('emails.users_new', $data, function($message) {
//
//                    $message->to('ce.pichardo@gmail.com', 'Receiver Name')->bcc('jrozon@ingenieriarozon.com')
//
//                            ->subject('Tuts Make Mail');
//        });

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
