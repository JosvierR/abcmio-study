@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.cities.update',$city) }}" method="POST" >
            @csrf
            @method('put')
            <div class="form-group row">
                <h3>Editando ciudad: {{$city->name}} del País: <strong>{{$city->country->name}}</strong></h3>
            </div>
            <div class="row">
                <a href="{{route('admin.countries.show',$city->country)}}" class="btn btn-primary">
                    {{trans('global.back')}}
                </a>
            </div>
            @include('admin.cities._form')
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
