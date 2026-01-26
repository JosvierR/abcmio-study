<?php

namespace App\Http\Controllers\Admin;

use App\Credit;
use App\Http\Requests\StoreCreditRequest;
use App\Http\Requests\UpdateCreditRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $credits  = Credit::all();
        return view('admin.credits.index',compact('credits'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.credits.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCreditRequest $request)
    {
        $credit = new Credit();
        $credit->fill($request->all());
        $credit->save();

        return redirect()->route('admin.credits.index')->with('success','Credito Creado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function show(Credit $credit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function edit(Credit $credit)
    {
        return view('admin.credits.edit',compact('credit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCreditRequest $request, Credit $credit)
    {
        $credit->update($request->all());
        return redirect()->route('admin.credits.index')->with('success','Actualizó Credito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Credit  $credit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Credit $credit)
    {
        //
    }
}
