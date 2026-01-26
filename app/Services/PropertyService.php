<?php

namespace App\Services;
use \App\Property;
use App\ReportOption;
use App\Repositories\PropertyRepository;

class PropertyService
{
    public function addNewVisitor(Property  $property)
    {
        $visitors = (new PropertyRepository())->getPropertyVisitors($property);
        $visitors += 1;
        (new PropertyRepository())->updatePropertyVisitors($property, $visitors);
        return $visitors;

    }

    static public  function getPropertyVisitors(Property  $property)
    {
        return (new PropertyRepository())->getPropertyVisitors($property);
    }

    public  function getReportOptions()
    {
        return ReportOption::orderBy('name', 'DESC')->get();
    }
}