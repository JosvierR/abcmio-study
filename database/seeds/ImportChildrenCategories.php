<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ImportChildrenCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        print("Start Children Categories: \n");
        $path = public_path()."/data/json/categories.json";
        $json = json_decode(file_get_contents($path), true);
        $categories = $json[2]['data'];
        foreach($categories as $category)
        {
            if($category['parent'] != 0)
            {
                if($parent = Category::find((int)$category['parent']))
                    $parent_id = $parent->id;
                else
                    $parent_id = 1;

                $slug = Str::slug($category['nombre']);
                print_r(['slug'=>$slug,'name'=>$category['nombre'],'parent_id'=>$parent_id,'is_free'=>false]);
//                break;

                if(!Category::where('slug',$slug)->first())
                    Category::create(['slug'=>$slug,'name'=>$category['nombre'],'parent_id'=>$parent_id,'is_free'=>false]);
            }else{
//                print("Parent: ".$category['nombre']);
//                print_r($category);
//                print("\n =================================================================================== \n");

            }
        }
        print("Completed Children Categories Import \n");
    }
}
