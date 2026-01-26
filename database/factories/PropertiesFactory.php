<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Property;
use App\Category;
use App\City;
use App\Action;
use App\User;
use Faker\Generator as Faker;

// $fillable = ['slug','title','category_id','city_id','is_public','action_id','status','website','image_path','short_description'
//    ,'description','comment','phone','email','address','show_email','show_website','show_phone','serial_number','google_map','send_message',
//    'start_date',"expire_date"
//];


$factory->define(Property::class, function (Faker $faker) {
    return [
        "user_id"=>$faker->randomElement(User::pluck("id")),
        "title"=>$faker->text(30),
        "category_id"=>$faker->randomElement(Category::pluck("id")),
        "city_id"=>$faker->randomElement(City::where("country_id",1)->pluck("id")),
        "is_public"=>1,
//        "action_id"=>$faker->randomElement(Action::pluck("id")),
        "website"=>$faker->url,
        "description"=>$faker->paragraph,
        "phone"=>$faker->phoneNumber,
        "email"=>$faker->companyEmail,
        "address"=>$faker->address,

    ];
});
