<?php

namespace App\Repositories;

use App\Country;

class LocationRepository
{
    public static function getCountries()
    {
        return Country::orderBy('name', 'ASC')->get();
    }
}