<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Country;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Country $country)
    {
        return view('admin.cities.create',compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $cityRequest)
    {
        $city = new City();
        $city->fill($cityRequest->all());
        $city->save();
        return redirect("/admin/countries/".$city->country->id)->with('success','Su ciudad fue creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('admin.cities.edit',compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $cityRequest, City $city)
    {
        $city->update($cityRequest->all());
        return redirect("/admin/countries/".$city->country->id)->with('success','Su ciudad fue editada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $country = $city->country->id;
        $city->delete();
        return redirect()->route("admin.countries.show",$country)->with('sucess','Ciudad Eliminada');
    }
}
