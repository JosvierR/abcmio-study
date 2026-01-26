<?php

use Illuminate\Database\Seeder;
use App\Property;

class AdsCreateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        factory(Property::class, 5)->create();
        factory(Property::class, 2500)->create()->each(function() {
//            $user->profile()->save(factory(App\Profile::class)->make());
        });

    }
}
