<?php

use App\City;
use App\Country;
use Illuminate\Database\Seeder;

class ImportGermanyCities extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        City::truncate();
        $path = public_path()."/data/json/cities-germany.csv";
//        $json = json_decode(file_get_contents($path), true);
//        $cities = $json[2]['data'];
        if($handle = fopen($path, "r"))
        {
            print "Start Import Germany City Only \n";
            while ($csvLine = fgetcsv($handle, 1000, ",")) {
                if(!empty(trim($csvLine[0])))
                {
                    $cityName = trim($csvLine[0]);
                    City::create(['name'=>$cityName,'country_id'=>258]);
//                    print "\n";
                }
            }
            fclose($handle);
            print "Finished Import Germany City Only \n";
        }else
            print "There is an error trying to open file $path \n";

//        foreach($cities as $city)
//        {
////            dd($city);
//            $slug = Str::slug($city['nombre']);
//
//            if($country = Country::find((int)$city['pais']))
//                City::create(['slug'=>$slug,'name'=>$city['nombre'],'country_id'=>258]);
//        }
//        print("Completed Cities Imported \n");
    }
}
