<?php
// test
namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Property;
use App\Http\Resources\Property as PropertyResource;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function get_city_by_country_id($country_id)
    {
        $cities = City::where('country_id',$country_id)->orderBy('name','asc')->get();
        return response()->json(['success'=>true,'result'=>$cities],200);
    }
    public function get_sub_categories_by_parent_id($parent_id)
    {
        $categories = Category::where('parent_id',$parent_id)->orderBy('name','asc')->get();
        return response()->json(['success'=>true,'result'=>$categories],200);
    }

    public function get_properies(Request $request)
    {
        $properties = Property::where('is_public',true)->with('category','city')->orderBy('id', 'desc')->paginate(50);
        return PropertyResource::collection($properties);
//        $properties = PropertyResource::collection();
        return response()->json(['success'=>true,'result'=>$result],200);
    }


    /*
     * Search Form API
     * */
    public function get_cities_by_params(Request $request,$country =1)
    {
        $category = null;
        $parent = null;

        if($request->has("country_id") && $request->country_id != -1)
            $country = $request->country_id;

        if($request->has("category_id") && $request->category_id != -1)
            $category = $request->category_id;

        if($request->has("parent") && $request->parent != -1)
            $parent = $request->category_id;

        return City::whereHas("properties", function($query) use ( $category, $parent){
                return $query->where("status","enable")
                    ->where("is_public",true)
                    ->whereHas("category",function($query) use ($category,$parent){
                        if(!is_null($parent))
                            $query->where("parent_id",$parent);
                        if(!is_null($category))
                            $query->where("category_id",$category);
                    });

        })
            ->where("country_id",$country)
            ->orderBy("name","ASC")->get();
    }

}
