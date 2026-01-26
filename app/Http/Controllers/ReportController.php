<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Property;
use App\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $post = $request->all();

        $reports = (new ReportService)->search($post['query'] ?? '');
        $this->setSectionName('Anuncios Reportados ' . $reports->count());
        return view('admin.reports.index', [
            'reports' => $reports
        ])->with($this->get_content_site($request, false));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreReportRequest $request)
    {
        $property_id = $request->get('report-id');
        $user_id = auth()->user()->id;
        $reported = (new ReportService)->isReported(Property::find($property_id));
        if(!$reported) {
            $data = ['user_id'=> $user_id, 'property_id' => $property_id, 'option_id' => $request->get('report-option')];
            Report::create($data);
            return response()->json(['success' => true, 'msg'=> 'Su reporte fue enviado.'], 200);
        }else {
            return response()->json(['success' => false, 'msg'=> 'Este anuncio ya fue reportado.'], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    public function remover($locale, $id)
    {
        $report = Report::find($id);
        $report->delete();
        return redirect()->back()->with('succes', 'Se removio el anuncio de la lista exitosamente');
    }

}
