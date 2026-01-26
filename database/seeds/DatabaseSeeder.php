<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        print("Start Importing: \n");
        // $this->call(UsersTableSeeder::class);
//         $this->call(CreateAdmins::class);
//         $this->call(CreateActions::class);
//         $this->call(CreateUsers::class);
//         $this->call(ImportCategories::class);
//         $this->call(ImportChildrenCategories::class);
//         $this->call(ImportCountries::class);
         $this->call(ImportCities::class);
//         $this->call(ImportProperties::class);
        print("Completed full Import \n");

    }
}
