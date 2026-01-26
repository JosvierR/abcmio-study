@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.credits.update',$credit) }}" method="POST" >
            @csrf
            @method('put')
            <div class="form-group row">
                <h3>Plan Crédito: {{$credit->name}} </h3>
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
                           value="{{old('name',$credit->name)}}"
                    >

                </div>
            </div>
            <div class="form-group row">
                <label for="price" class="col-sm-2 col-form-label">Precio <small class="required">*</small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="price" id="price"
                           value="{{old('price',$credit->price)}}"
                    >

                </div>
            </div>
            <div class="form-group row">
                <label for="total" class="col-sm-2 col-form-label">Total Créditos <small class="required">*</small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="total" id="total"
                           value="{{old('total',$credit->total)}}"
                    >

                </div>
            </div>

            {{--            <textarea name="mailEditor" id="txtEditor"></textarea>--}}
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
