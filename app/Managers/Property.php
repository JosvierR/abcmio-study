<?php


namespace App\Managers ;
use App\Http\Resources\Property as PropertyResource;
use App\Property as PropertyApp;


class Property
{

    public static function get($arg = [])
    {
        return PropertyApp::all();
    }

}