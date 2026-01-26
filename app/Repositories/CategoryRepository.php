<?php

namespace App\Repositories;

use App\Category;

class CategoryRepository
{
    public static function all()
    {
        return Category::where('parent_id', 0)->orderBy('name', 'ASC')->get();
    }
}