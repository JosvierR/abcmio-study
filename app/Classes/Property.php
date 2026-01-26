<?php


namespace App\Classes;
use App\Property as Prop;
use Illuminate\Http\Request;

class Property
{
    static function get(Request $request)
    {
        return $request->all();
    }
}