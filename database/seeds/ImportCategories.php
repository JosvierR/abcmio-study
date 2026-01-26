<?php

use Illuminate\Database\Seeder;
use App\Category;
use Illuminate\Support\Str;

class ImportCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        print("Start Categories: \n");
        $path = public_path()."/data/json/categories.json";
        $json = json_decode(file_get_contents($path), true);
        Category::truncate();
        Category::create(['slug'=>'otros','name'=>'Otros','parent_id'=>0,'is_free'=>false]);
        $categories = $json[2]['data'];
        foreach($categories as $category)
        {
            if($category['parent'] == 0)
            {
                $slug = Str::slug($category['nombre']);
                Category::create(['id'=>$category['id'],'slug'=>$slug,'name'=>$category['nombre'],'parent_id'=>0,'is_free'=>false]);
            }
        }
        print("Completed Categories Import \n");
    }
}
