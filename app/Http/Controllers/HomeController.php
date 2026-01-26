<?php

namespace App\Http\Controllers;

use App\Property;
use App\Country;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
////        $this->middleware(['auth'=>'verified']);
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        where('is_public',true)->
//        Property::where('city_id',null)->delete();
//        $properties = Property::where('city_id',null)->take(100)->get();
        $countries = Country::orderBy('name','ASC')->get();
//        $properties = Property::where('is_public',true)->orderBy('id','DESC')->take(100)->get();
//        dd($properties[0]->city->name);
        return view('home',compact('properties','countries'));
    }

    public function verify_email($token)
    {
        $user = User::where('token',$token)->first();
        \Auth::login($user);
        $countries = Country::orderBy('name','ASC')->get();
        return view('frontend.account.profile',compact('countries','user'))
            ->with('message','Por favor ingrese su contraseña.');
    }

    public function redirectHome()
    {
        return redirect()->route('home', app()->getLocale());
    }
    public function redirectSlug($slug)
    {
        return redirect()->route('get.property.by.slug', [app()->getLocale(), $slug]);
    }

}
