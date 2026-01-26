<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['price','total_credit','status','description'];

//    public function __construct(Credit $credit = null)
//    {
//        if(!is_null($credit))
//        {
//            $this->price = $credit->price;
//            $this->total_credit= $credit->total;
//            $this->description= $credit->name;
//            $this->status= "pending";
//        }
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function credit()
    {
        return $this->hasOne(Credit::class);
    }
}
