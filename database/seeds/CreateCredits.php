<?php

use Illuminate\Database\Seeder;
use App\Credit;

class CreateCredits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Credit::create(['name'=>'Plan Regular','price'=>3,'total'=>10]);
        Credit::create(['name'=>'Plan Intermedio','price'=>10,'total'=>25]);
        Credit::create(['name'=>'Plan Premium','price'=>30,'total'=>50]);
    }
}
