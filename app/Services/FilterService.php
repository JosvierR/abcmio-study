<?php


namespace App\Services;

use App\Property;

class FilterService
{
    /*
     *   "country_id" => "1"
          "category_id" => "-1"
          "query" => null
          "btn_search" => null
     * */

    static public function generateDataFilter($get_request = [], $user = null): array
    {
        $dataFilter =
            [
                'query' => $get_request["query"] ?? null,
                'city' => $get_request["city"] ?? null,
                'exact_match' => $get_request['exact_match'] ?? null,
                'published' => $get_request['is_publish'] ?? false,
                'country' => $get_request['country_id'] ?? null,
                'is_public' => $get_request['is_public'] ?? $get_request['is_publish'] ?? null,
//                'is_public' => $get_request['is_publish'] ?? null,
                'user' => $user->id ?? null,
                'category' => [
                    "parent" => $get_request['category_id'] ?? null,
                    "category" => $get_request['sub_category_id'] ?? null
                ],
            ]; //Get al product from
        return $dataFilter;
    }
}
