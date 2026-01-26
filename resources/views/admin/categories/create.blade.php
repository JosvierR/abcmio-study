@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')
    <div class="container">
        <form class="form-horizontal" id="form_create" action="{{ route('admin.categories.store') }}" method="POST" >
            @csrf
            @method('post')
            @include('admin.categories._form')
            {{--            <textarea name="mailEditor" id="txtEditor"></textarea>--}}
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Crear</button>
                </div>
            </div>
        </form>
    </div>
@endsection
