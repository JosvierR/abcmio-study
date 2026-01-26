<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $string = "";
        if($request->has("query"))
            $string = $request["query"];
        $countries  = Country::where(function($query) use ($string){
            $query->orWhere('name','like', '%' . $string . '%');
        })
//            ->withCount('cities')
            ->orderBy('name','ASC')->paginate(50);
        return view('admin.countries.index',compact('countries','string'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCountryRequest $storeCountryRequest)
    {
        $country = new Country();
        $country->fill($storeCountryRequest->all());
        $country->save();
        return redirect('/admin/countries')->with('success','Su país fue creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request,Country $country)
    {
        $string = "";
        if($request->has("query"))
            $string = $request["query"];

        $cities = $country->cities()->where(function($query) use ($string) {
            $query->orWhere('name','like', '%' . $string . '%');
        })->paginate(50);
        return view('admin.countries.show',compact('cities','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        return view('admin.countries.edit',compact('country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCountryRequest $countryRequest, Country $country)
    {
        $country->update($countryRequest->all());
        return redirect('/admin/countries')->with('success','Su país fue creado correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {

    }
}
