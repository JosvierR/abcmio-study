<?php

use Illuminate\Database\Seeder;
use App\City;
use App\Country;

class Locations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        $path = storage_path() . "/json/${filename}.json"; // ie: /var/www/laravel/app/storage/json/filename.json
//        if(file_exists())
//        $json = json_decode(file_get_contents($path), true);
//        $path = public_path()."/data/json/paises.json";
        $path = public_path()."/data/json/ciudades.json";
        $json = json_decode(file_get_contents($path), true);

//        dd($json[2]['data']);
//        dd($json[2]['data']);
//        $countries = $json[2]['data'];
//        foreach($countries as $country)
//        {
//            print_r($country);
//
//            Country::create(['name'=>$country['nombre'],'id'=>$country['id']]);
//            echo "\n";
//        }
        $cities = $json[2]['data'];
        foreach($cities as $city)
        {
//            dd($city);
            if($country = Country::find((int)$city['pais']))
                City::create(['name'=>$city['nombre'],'country_id'=>$country->id]);
            else
                echo "Not found \p";
//            $country->cities()->save(  );

        }
    }
}
