<?php

use App\Country;
use App\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImportCities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        print("Start Cities importing: \n");
        City::truncate();
        $path = public_path()."/data/json/ciudades.json";
        $json = json_decode(file_get_contents($path), true);
        $cities = $json[2]['data'];
        foreach($cities as $city)
        {
//            dd($city);
            $slug = Str::slug($city['nombre']);

            if($country = Country::find((int)$city['pais']))
                City::create(['slug'=>$slug,'name'=>$city['nombre'],'country_id'=>$country->id]);
        }
        print("Completed Cities Imported \n");
    }
}
