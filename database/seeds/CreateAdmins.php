<?php

use Illuminate\Database\Seeder;

class CreateAdmins extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::truncate();
        \App\User::create(['name'=>'Edmundo Pichardo','type'=>'super','email'=>'ce.pichardo@gmail.com','password'=>\Hash::make('12345678')]);
    }
}
