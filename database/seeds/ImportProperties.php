<?php

use App\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Category;
use App\Action;
use App\City;

class ImportProperties extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        ["id" => "250",
//  "nombre" => "YAMAHA MOTIF ES6 (COMO NUEVO/NEGOCIABLE)",
//  "anyo" => "2009",
//  "modelo" => "MOTIF ES6",
//  "registro" => "",
//  "ciudad" => "1",
//  "descr" => "YAMAHA MOTIF ES6 (COMO NUEVO/NEGOCIABLE)",
//  "direccion" => "YAMAHA MOTIF ES6 (COMO NUEVO/NEGOCIABLE)",
//  "pais" => "1",
//  "pais_iso" => "DO",
//  "otropais" => "",
//  "otraciudad" => "",
//  "tipo" => "821",
//  "otrotipo" => "",
//  "status" => "2",
//  "fecha" => "2009-07-28",
//  "hora" => "13:00:00",
//  "fecham" => "2009-07-28",
//  "fechav" => "2009-07-28",
//  "usuario" => "776",
//  "contactopublico" => "0",
//  "privacidad" => "1",
//  "precio" => "60000",
//  "divisa" => "1",
//  "img" => "5a37d71c36a3abb9b34bf8b4a3d74329.jpg",
//  "coment_img1" => "",
//  "img2" => "",
//  "img3" => "",
//  "img4" => "",
//  "map" => "",
//  "comentarios" => "NEGOCIABLE",
//  "website" => ""];
        print("Start Importing Properties: \n");
        Property::truncate();
        $path = public_path()."/data/json/propiedades.json";
        $json = json_decode(file_get_contents($path), true);


        $properties = $json[2]['data'];
        foreach($properties as $property)
        {
            $slug = Str::slug($property['nombre']);
            if($category = Category::find($property['tipo']))
                $category_id = $category->id;
            else
                $category_id = 1;

            if($city = City::find($property['ciudad']))
                $city_id = $city->id;
            else
                $city_id = null;


//            dd($property);
            $name = Str::limit($property['nombre'],150);
            $slug = Str::slug($name);
            $prop = [
                'id'=>$property['id'],
                'user_id'=>\App\User::first()->id,
                'slug'=>$slug,
                'title'=>$name,
                'category_id'=>$category_id,
                'city_id'=>$city_id,
                'is_public'=>!(int)$property['privacidad'],
                'action_id'=>Action::first()->id,
                'status'=>'enable',
                'website'=>Str::limit($property['website'],150),
                'image_path'=>null,
                'short_description'=>Str::limit($property['descr'],150),
                'description'=>$property['descr'],
                'comment'=>$property['comentarios'],
                'phone'=>null,
                'show_email'=>$property['contactopublico'],
                'show_website'=>true,
                'serial_number'=>null,
                'google_map'=>null,
                'send_message'=>false
            ];
//            dd($prop);
//            print_r($prop);
            Property::create($prop);
//            Country::create(['slug'=>$slug,'name'=>$property['nombre'],'id'=>$country['id']]);
        }
        print("Completed Properties imported \n");
    }
}
