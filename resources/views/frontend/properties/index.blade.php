@extends('frontend.layouts.app')
@section('scripts')
{{--    <script src="{{ asset('js/search.js') }}"></script>--}}
@stop


@section('content')
    @include('frontend.common.profile-search',['searchUrl' => $searchUrl ])
    <br>
    <div class="row form-group m-1">
        <a href="{{route('properties.create', app()->getLocale())}}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            {{trans('pages.my_ads.buttons.create-add')}}
        </a>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">

{{--            <div class="card">--}}
{{--                <div class="card-header">Mis Anuncios ({{\Auth::user()->properties->count()}})</div>--}}
                <h3><small>{{trans('pages.my_ads.labels.search_result')}} ({{$properties->total()}})</small></h3>

{{--                <h3><small>{{trans('pages.my_ads.labels.search_result')}} ({{$properties->count()}})</small></h3>--}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        <section>
                            {{$properties->onEachSide(5)->links()}}
                        </section>
                        <section id="directory-products">
                            <ul>
                                @foreach($properties as $property)
                                    <li>
                                        <article>
                                            <a href="{{route('properties.edit',[app()->getLocale(), $property])}}"><h3 class="text-break">{{$property->title}}</h3></a>
                                            <br>
                                            <div class="picture-thumb col-12 d-block mb-2">
                                                <img class="img-fluid mx-auto d-block" src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}"/>
                                            </div>
                                            <div class="info">
                                                <h4> @if($property->category){{($property->category->parent)?$property->category->parent->name." / ":''}}  {{$property->category->name}}@endif</h4>
                                                <h5>@if($property->city){{$property->city}}/ {{$property->city->country->name ?? ''}}@endif</h5>
                                                <div class="form-group">
                                                    <table class="datatable">
                                                        @if($property->is_public)
                                                            <tr>
                                                                <td>
                                                                    {{trans('pages.my_ads.info_table.title')}}
                                                                </td>
                                                                <td><small><strong>{{$property->created_at->diffForhumans()}}</strong></small></td>
                                                            </tr>
                                                            @if($property->start_date)
                                                                <tr>
                                                                    <td>
                                                                        {{trans('pages.my_ads.info_table.start_date')}}
                                                                    </td>
                                                                    <td>
                                                                        <small><strong>{{$property->start_date->format('d/m/Y') ?? ''}}</strong></small>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            @if($property->expire_date)
                                                                <tr>
                                                                    <td>
                                                                        {{trans('pages.my_ads.info_table.end_date')}}
                                                                    </td>
                                                                    <td>
                                                                        <small><strong>{{$property->expire_date->format('d/m/Y') ?? ''}}</strong></small>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>{{trans('pages.my_ads.info_table.remaining')}}</td>
                                                                    <td><small><strong>{{$property->TimeLeft ?? ''}}</strong></small></td>
                                                                </tr>
                                                            @endif
                                                            @if($property->user->credits>0)
                                                                <tr>
                                                                    <td><a href="{{route("extend.form",[app()->getLocale(), $property])}}" class="btn btn-primary btn-sm">{{trans('pages.my_ads.info_table.buttons.extend')}}</a></td>
                                                                    <td><a href="{{route("privating.store",[app()->getLocale(), $property])}}" class="btn btn-warning btn-sm">{{trans('pages.my_ads.info_table.buttons.private')}}</a></td>
                                                                </tr>
                                                            @endif
                                                        @else
                                                            <tr>
                                                                <td><a href="{{route('publish.form',[app()->getLocale(), $property])}}" class="btn btn-success btn-sm">{{trans('pages.my_ads.info_table.buttons.publish')}}</a></td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td>
                                                                <form action="{{route('properties.destroy',[app()->getLocale(), $property])}}" method="POST" class="act-delete deleteGroup"

                                                                >
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit" class="btn action action-btn btn-danger btn-sm">
                                                                        <i class="fas fa-trash trash" data-toggle="tooltip" data-placement="top" title="Borrar: {{$property->name}} "> </i> {{trans('pages.my_ads.info_table.buttons.delete')}}
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <p class="actions">
                                                    <a href="{{route('properties.edit',[app()->getLocale(), $property])}}"><i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="Editar Anuncio"></i></a>
                                                    <a href="#">
                                                        @if($property->is_public)
                                                        @else
                                                            <a href="{{route('publish.form',[app()->getLocale(), $property])}}">
                                                                <i class="fas fa-check-double green publish" data-toggle="tooltip" data-placement="top" title="Publicar Anuncio"></i>
                                                            </a>
                                                        @endif
                                                    </a>
                                                </p>

                                            </div>
                                        </article>
                                        <div class="clearFix"></div>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    <section>
                        {{$properties->onEachSide(5)->appends(request()->query())->links()}}
                    </section>
                </div>
        </div>
        <div class="col-md-4">
        </div>
    </div>
    @include('partials._scroll_top')
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function($){
            $('.deleteGroup').on('submit',function(e){
                if(!confirm('Do you want to delete this item?')){
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush

