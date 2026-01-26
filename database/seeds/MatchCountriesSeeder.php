<?php

use Illuminate\Database\Seeder;

class MatchCountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        print "\n";
        print "Match: ";
        print "\n";
        foreach(\App\Property::all() as $property) {
            $c = \App\City::find($property->city_id);
            $country_id = $c->country->id ?? -1;
            if($country_id != -1) {
                print ".";
                $property->country_id = $country_id;
                $property->save();
            }
        }
        print "\n";
        print "Completed";
        print "\n";

    }
}
