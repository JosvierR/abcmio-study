<?php

use Illuminate\Database\Seeder;
use App\Action;

class CreateActions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::truncate();
        Action::create(['slug'=>'promover','name'=>'Promover']);
    }
}
