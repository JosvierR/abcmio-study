<?php


namespace App\Services;


class Property
{
    protected $property;
    public function __construct(\App\Property $property)
    {
        $this->property = $property;
    }

    static public function get()
    {

    }
}
