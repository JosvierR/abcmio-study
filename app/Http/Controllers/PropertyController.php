<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExtendingPropertyRequest;
use App\Http\Requests\PublishingPropertyRequest;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Property;
use App\Report;
use App\Repositories\PropertyRepository;
use App\Services\FilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

use App\Mail\SentMessageFromProductDetailMail;
use Session;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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


    /**
     * Mis Anuncios section
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response | mixed
     * "query": "Hola",
     *  "is_publish": "1"
     */
    public function index(Request $request)
    {
        $this->setSectionName(trans('pages.my_ads.header.title'));
        $this->setSearchUrl('search.property'); // My Properties Result Url
        return view('frontend.properties.index')
            ->with($this->get_content_site($request, true, true));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->setSectionName(trans('pages.forms.ads.create.title'));
        return view('frontend.properties.create')->with($this->get_content_site());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($locale, StorePropertyRequest $storePropertyRequest)
    {
        $response = (new PropertyRepository)->create($storePropertyRequest);

        return redirect()->route('properties.index', app()->getLocale())
            ->with('success',
                trans('properties.actions.created.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Property $property
     * @return \Illuminate\Http\Response
     */
    public function show($locale, $id)
    {
        if (!is_null($id)) {
            $property = Property::findOrFail($id);
            if(auth()->user()->id != $property->user->id) {
                return redirect()->route('properties.index', app()->getLocale());
            }

            return redirect()->route('get.property.by.slug', [app()->getLocale(), $property->slug]);
            return view('frontend.properties.show', compact('property'))->with($this->get_content_site(null, null, null,
                ['property' => $property]));
        }
        return view('errors.404');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Property $property
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($locale, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        return view('frontend.properties.edit',
            compact('property', 'locale', 'property'))
            ->with($this->get_content_site(null, null, null));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Property $property
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update($local, UpdatePropertyRequest $updatePropertyRequest, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        $response = (new PropertyRepository)->update($property, $updatePropertyRequest);
        return redirect()->route('properties.index', [app()->getLocale(), $property])->with('success',
            trans('properties.actions.updated.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($locale, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        if ((new PropertyRepository)->delete($property)) {
            return redirect()->route('properties.index', app()->getLocale())->with('success',
                trans('properties.actions.deleted.success'));
        }
        return redirect()->route('properties.index',
            app()->getLocale())->withErrors(trans('properties.actions.deleted.error'));
    }

    public function publish($locale, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        return view('frontend.properties.publish_form', compact('property'))
            ->with($this->get_content_site(null, null,
                null, ['property' => $property]));
    }

    public function extended($locale, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        return view('frontend.properties.extended_form', compact('property'))->with($this->get_content_site(null, null,
            null, ['property' => $property]));
    }

    public function publishing($locale, PublishingPropertyRequest $request, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        $property = (new PropertyRepository)->make('publish', $request->all(), $property);

        if ($property) {
            return redirect()->route('properties.index', app()->getLocale())->with('success',
                trans('properties.actions.published.success'));
        }

        return redirect()->route('properties.index',
            app()->getLocale())->withErrors(trans('properties.actions.published.success'));
    }

    public function extending($locale, ExtendingPropertyRequest $request, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        if ($propery = (new PropertyRepository)->make('extend', $request, $property)) {
            return redirect()->route('properties.index', app()->getLocale())->with('success',
                trans('properties.actions.extended.success'));
        }
        return redirect()->route('properties.index',
            app()->getLocale())->withErrors(trans('properties.actions.extended.error'));
    }

    public function privating($locale, Property $property)
    {
        if(auth()->user()->id != $property->user->id ) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        if ((auth()->check() && $property->user->id == auth()->user()->id) ) {
            $property->update(['is_public' => false, "start_date" => null, "expire_date" => null]);
            return redirect()->back()->with('success',
                trans('properties.actions.private.success'));
        }
    }

    public function adminPrivate($locale, Property $property)
    {
        if (auth()->check() && in_array(auth()->user()->type, ['admin', 'super'])) {
            $property->update(['is_public' => false, "start_date" => null, "expire_date" => null]);
            Report::where('property_id', $property->id)->delete();
            return redirect()->back()->with('success',
                trans('properties.actions.private.success'));
        }

        return redirect()->back()->withErrors('Hubo un problema al tratar de privar el anuncio');
    }

    /**
     * Send Message to owner product
     */
    public function message($locale, Request $request, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        Mail::to([$property->user->email])->send(new SentMessageFromProductDetailMail($property, $request->all()));
        return redirect()->back()->with('success', 'Su mensaje fue enviado');
    }

    public function upload($locale, Request $request, Property $property)
    {
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        try {
            $property->addMedia($request->file)->toMediaCollection("gallery");
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                'msg' => 'Upload error ' ,
                'exception' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
        $property->refresh();
        return response()->json(['property' => $property, 'success' => true], Response::HTTP_OK);

        $ouput = [];
        foreach ($property->getMedia('gallery') ?? [] as $media) {
            $ouput[] = ['url' => $media->getUrl('thumb'), 'key' => $media->id];
        }
        return response()->json(['data' => $ouput], 200);
    }

    public function delete($locale, Request $request, $id)
    {

        $property = Property::findOrFail($id);
        if(auth()->user()->id != $property->user->id) {
            return redirect()->route('properties.index', app()->getLocale());
        }
        if ($property->user_id == auth()->user()->id) {
            if ($media = $property->getMedia('gallery')->where('id', $request->get('key'))->first()) {
                $media->delete();
                return response()->json(['message' => 'Foto borrada', 'success' => true], 200);
            } else {
                return response()->json([], 404);
            }
        } else {
            return response()->json([], 405);
        }
    }
}
