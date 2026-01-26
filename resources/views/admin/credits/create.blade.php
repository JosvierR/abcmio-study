@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.credits.store') }}" method="POST" >
            @csrf
            @method('post')
            <div class="form-group row">

            </div>
            <div class="row">
                <a href="{{route('admin.credits.index')}}" class="btn btn-primary">
                    {{trans('global.back')}}
                </a>
            </div>
            <div class="form-group row">

            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Nombre <small class="required">*</small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name"
                           value="{{old('name')}}"
                    >

                </div>
            </div>
            <div class="form-group row">
                <label for="price" class="col-sm-2 col-form-label">Precio <small class="required">*</small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="price" id="price"
                           value="{{old('price')}}"
                    >

                </div>
            </div>
            <div class="form-group row">
                <label for="total" class="col-sm-2 col-form-label">Total Créditos <small class="required">*</small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="total" id="total"
                           value="{{old('total')}}"
                    >

                </div>
            </div>

            {{--            <textarea name="mailEditor" id="txtEditor"></textarea>--}}
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
@endsection
