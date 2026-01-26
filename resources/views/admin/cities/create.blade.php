@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.cities.store') }}" method="POST" >
            @csrf
            @method('post')
            <div class="form-group row">
                <h3>Creando Cidad en {{$country->name}}</h3>

            </div>
            <div class="row">
                <a href="{{route('admin.countries.show',$country)}}" class="btn btn-primary">
                    {{trans('global.back')}}
                </a>
            </div>
            @include('admin.cities._form')
            {{--            <textarea name="mailEditor" id="txtEditor"></textarea>--}}
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
@endsection
