<?php

use Illuminate\Database\Seeder;

class CreateNewAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(['name'=>'Laura Rozón','type'=>'super','email'=>'laura_rozon@yahoo.es','password'=>\Hash::make('12345678')]);
        \App\User::create(['name'=>'José Rozón','type'=>'super','email'=>'joserozondirect@gmail.com','password'=>\Hash::make('12345678')]);
        \App\User::create(['name'=>'Marianita Henriquez','type'=>'super','email'=>'marianitahenriquez@hotmail.com','password'=>\Hash::make('12345678')]);
    }
}
