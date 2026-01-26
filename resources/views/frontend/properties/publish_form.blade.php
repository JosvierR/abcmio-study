@extends('frontend.layouts.app')

@section('scripts')
    <script src="{{asset('js/properties.js')}}"></script>

@stop
@section('styles')
{{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">--}}
@stop

@section('content')
    <div class="container">

        <div class="form-group row">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <a href="{{route('properties.index', app()->getLocale())}}" class="btn btn-danger">{{trans('global.back')}}</a>
                </div>
                <div class="p-2">
                    @if(isset($property))
                        <a href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}" class="btn btn-primary">{{trans('global.view')}}</a>
                    @endif
                </div>
            </div>

        </div>

        {{--        <h3>Arrastre y deje caer las imagenes que desea subir</h3>--}}
        {{--        <form method="post" action="{{url('api/photos')}}" enctype="multipart/form-data"--}}
        {{--              class="dropzone" id="dropzone">--}}
        {{--            <input type="hidden" name="property" value="{{$property->id}}">--}}
        {{--            @csrf--}}
        {{--        </form>--}}
        <form class="form-horizontal" id="form_create" action="{{ route('publish.store',[app()->getLocale(),$property]) }}" method="POST" enctype="multipart/form-data" >
            @csrf
            <div class="form-group row">
                <div class="col-sm-10">
                    <h2><strong>ID {{$property->id}}</strong>, {{trans('properties.publish.property_title')}}: {{$property->title}}</h2>
                    <div class="alert alert-primary" role="alert">
                        {{trans('global.view')}}: <a target="_blank" href="{{route('get.property.by.slug',[app()->getLocale(),$property->slug])}}" class="alert-link">https://abcmio.com/{{$property->slug}}</a>.
                    </div>

                </div>
            </div>
            <div class="form-group row">
                <label for="start_date" class="col-sm-2 col-form-label"><h3>{{trans('properties.publish.start_date_title')}}  </h3></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control  datepicker" disabled name="start_date" id="start_date"
                           placeholder="Campo requerido"
                           value="{{old('start_date',$property->start_date ?? date('d/m/Y'))}}"
                    >
                </div>
            </div>
            <div class="form-group row">
                <label for="title" class="col-sm-2 col-form-label"><h3>{{trans('properties.publish.select_days_title')}}  <small class="required">*</small></h3></label>
                <div class="col-sm-10">
{{--                    <input type="text" class="form-control required" name="title" id="title"--}}
{{--                           placeholder="Campo requerido"--}}
{{--                           value="{{isset($property)?$property->title:(empty(old("title"))?"":old('title'))}}"--}}
{{--                    >--}}
                    <select name="days" id="">
                        @for($i=$property->user->credits>365?365:$property->user->credits; ($i<= $property->user->credits &&  $i>0) ;$i--)
                                <option value="{{$i}}">{{$i}} {{trans('properties.publish.days_title')}}{{$i>1?'s':''}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">{{trans('properties.publish.title')}}</button>
                </div>
            </div>
        </form>
        {{--        <form method="post" action="{{route('photos.store')}}" enctype="multipart/form-data">--}}
        {{--            @csrf--}}
        {{--            @method('post')--}}


        {{--            <div class="row">--}}
        {{--                <div class="col-md-4"></div>--}}
        {{--                <div class="form-group col-md-4">--}}
        {{--                    <button type="submit" class="btn btn-success" style="margin-top:10px">Upload Image</button>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--        </form>--}}

    </div>
@endsection
