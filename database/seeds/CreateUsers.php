<?php

use Illuminate\Database\Seeder;
use App\User;

class CreateUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::create(['name'=>'Anuncios Promocionales','email'=>'abcanuncios@gmail.com','password'=>\Hash::make('12345678')]);
    }
}
