<?php

use Illuminate\Database\Seeder;
use App\Country;
use Illuminate\Support\Str;

class ImportCountries extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        print("Start Contries: \n");
        Country::truncate();
        $path = public_path()."/data/json/paises.json";
        $json = json_decode(file_get_contents($path), true);
        $countries = $json[2]['data'];
        foreach($countries as $country)
        {
            $slug = Str::slug($country['nombre']);
            Country::create(['slug'=>$slug,'name'=>$country['nombre'],'id'=>$country['id']]);
        }
        print("Completed Countries Imported \n");
    }
}
