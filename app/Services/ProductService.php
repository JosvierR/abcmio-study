<?php


namespace App\Services;

use App\Property;
use Carbon\Carbon;

class ProductService
{
    static protected $limit = 50;

    /**
     * Get Product Filtered by Array
     *
     * array:5 [▼
     * "query" => null
     * "country" => "1"
     * "category" => "-1"
     * "sub_category" => null
     * ]
     *
     */
    static public function getFilteredProduct($dataFilter = [])
    {
        $sortBy = "created_at";
        $orderBy = "ASC";

        if(auth()->check()) {
            $orderBy = "DESC";
         } else {
             $orderBy = "ASC";
        }
        
        if (\request()->has("s")) {
            $sortBy = request()->input("s"); //sortBy price/ date
            $orderBy = request()->input("o"); // orderBy "ASC" , "DESC"
        }
            return Property::with([
                    'category',
                    'media',
                    'country'
                ])
                ->filter($dataFilter)
                ->orderBy($sortBy, $orderBy)
                ->where("status", "enable")
                ->where("is_public", true)
                ->paginate(self::$limit);

    }

    static function getMyAdsByFilter($dataFilter = [])
    {
        $sortBy = "id";
        $orderBy = "DESC";

//        if (\request()->has("s")) {
//            $sortBy = request()->input("s"); //sortBy price/ date
//            $orderBy = request()->input("o"); // orderBy "ASC" , "DESC"
//        }
        return Property::with([
            'category',
            'media',
            'country',
            'user'
        ])
            ->filter($dataFilter)
            ->orderBy($sortBy, $orderBy)
            ->paginate(self::$limit)
            ->appends(request()->query());

    }
}
