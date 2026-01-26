<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\Country;
use App\Property;
use App\ReportOption;
use App\Services\FilterService;
use App\Services\ProductService;
use App\Services\PropertyService;
use App\Services\ReportService;
use App\Visitor;
use Illuminate\Http\Request;

use Session;

class DirectoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($locale, Request $request)
    {

        $this->setSectionName(trans('nav.header.nav.directory'));
        $options = (new ReportService)->getOptions();
        $this->setSearchUrl('search.results');

        return view('frontend.directories.index', compact( 'options'))
            ->with($this->get_content_site($request, true));
    }

    public function show($id)
    {
        if (!is_null($id)) {
            $property = Property::findOrFail($id);
//            if($property = Property::where('slug',$slug)->first())
            return redirect("/{$property->slug}");
            return view('frontend.properties.show', compact('property'))->with($this->get_content_site());
        }
        return view('errors.404');
    }

    /**
     * Vista de datelle principal
    */
    public function get_property_by_slug($locale, $slug = null)
    {
        if (!is_null($slug)) {
            if ($property = Property::where('slug', $slug)->first()) {
                if(

                    (!auth()->check()  &&  !$property->is_public) ||
                    (auth()->check() && auth()->user()->id != $property->user_id && !$property->is_public) ) {
                    return redirect()->route('home', app()->getLocale())->with('warning','This Ad is not published...');
                }


                (new PropertyService())->addNewVisitor($property);
                $reportOptions = (new PropertyService())->getReportOptions();
                return view('frontend.properties.show', compact('property', 'reportOptions'))
                    ->with($this->get_content_site(null, null, null , ['property' => $property]));
            }
        }
        return view('errors.404');
    }

}
