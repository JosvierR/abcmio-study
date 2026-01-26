<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mail::to('ce.pichardo@gmail.com')->send(new \App\Mail\TestMail());
    }
}
