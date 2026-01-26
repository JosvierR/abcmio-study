<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Session;

class SearchController extends Controller
{
    protected $limit = 20;

    public function buildQuery(Request $request)
    {
        $get_request = $request->except(['_token', '_method']);
        if (session()->has("get_request")) {
            Session::forget("get_request");
        }
        Session::put('get_request', $get_request);

        return redirect()->route("search.index");
    }

    public function index($get_request = [], $reset = false)
    {
        if ($reset) {
            Session::forget("get_request");
        }

        if (session()->has("get_request")) {
            $get_request = Session::get("get_request", []);
        }
        $post = $get_request;
        \DB::enableQueryLog();
        $dataFilter = FilterService::generateDataFilter($get_request);
        $properties = ProductService::getFilteredProduct($dataFilter);

        $query = \DB::getQueryLog();
        $query = end($query);

        return view('frontend.directories.index', compact('properties'))->with($this->get_content_site($post));
    }
}
