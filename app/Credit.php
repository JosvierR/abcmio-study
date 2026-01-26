<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = ['name','total','price','status'];

    public function getTotalPriceAttribute()
    {
        return "US$ ".number_format($this->price, 2, ',', ' ');;
    }
}
