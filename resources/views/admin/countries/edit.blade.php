@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.countries.update',$country) }}" method="POST" >
            @csrf
            @method('put')
            <div class="form-group row">
                <h3>Editando país: <strong>{{$country->name}}</strong></h3>

            </div>
            @include('admin.countries._form')
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
