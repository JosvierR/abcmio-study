<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class PropertyFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];
//    ['slug','title','category_id','city_id','is_public','action_id','status','website','image_path','short_description'
//        ,'description','comment','phone','email','address','show_email','show_website','show_phone','serial_number','google_map','send_message',
//        'start_date',"expire_date"
//    ];

    /**
     * search request field
     * @param $name
     * @return PropertyFilter
     */
    public function query($value)
    {
//        $string = $array['string'] ?? null;
//        $exact_match = $array['exact_match'] ?? false;
        $string = strtolower(trim($value));
        return $this->when($string, function($query) use ($string) {
            $query->where(function($query) use ($string) {
                $query->where('title', 'LIKE', "%" . $string . "%")
                    ->orWhere('slug', 'LIKE', "%" . $string . "%")
                    ->orWhere('state', 'LIKE', "%" . $string . "%")
                    ->orWhere('description', 'LIKE', "%" . $string . "%");
            });

        });
//        return $this->where(function ($query) use ($string) {
//            if (!empty($string)) {
//                if (!$exact_match) {
//                    return $query->where('title', 'LIKE', "%" . $string . "%")
//                        ->orWhere('slug', 'LIKE', "%" . $string . "%")
//                        ->orWhere('description', 'LIKE', "%" . $string . "%");
//                } else {
//                    return $query->orWhere('title', $string)->orWhere("description", $string);
//                }
//            }
//        });
    }


    public function city($value)
    {
        $string = strtolower(trim($value));
        return $this->when($string, function($query) use ($string) {
            $query->where(function($query) use ($string) {
                $query->where('city', 'LIKE', "%" . $string . "%");
            });

        });
    }

    /**
     * category request field
     */
//    public function  subcategory($id)
//    {
//        if(!is_null($id) && $id != -1)
//            return $this->where("category_id",$id);
//    }

    public function category($array)
    {
        $parent = $array["parent"] ?? null;
        $category = $array['category'] ?? null;
        return $this->whereHas('category', function ($query) use ($parent, $category) {
            if (!is_null($parent) && $parent > 0) {
                $query->where("parent_id", $parent);
            }
            if (!is_null($category) && $category > 0) {
                $query->where("category_id", $category);
            }
        });
    }

    public function country($id)
    {
        return $this->when($id > 0 , function($query) use ($id) {
            return $query->where('country_id', $id);
        });
//        if (!is_null($id) && $id > 0) {
//            return $this->whereHas('city', function ($query) use ($id) {
//                if (!is_null($id) && $id > 1) {
//                    return $query->where('country_id', $id);
//                }
//            });
//        }
    }


    public function user($id)
    {
        return $this->when($id, function ($query) use ($id) {
            $query->where('user_id', $id);
        });
    }

    public function isPublish($isPublish)
    {
        return $this->when($isPublish, function ($query) use ($isPublish) {
            $query->where('is_public', $isPublish);
        });
    }

    public function isPublic($isPublish)
    {
        return $this->when($isPublish, function ($query) use ($isPublish) {
            $query->where('is_public', $isPublish);
        });
    }

}
