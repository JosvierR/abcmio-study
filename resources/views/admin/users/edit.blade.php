@extends('frontend.layouts.app')

@section('scripts')
@stop
@section('styles')
@stop

@section('content')

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">{{$user->name}}</h1>
            <p class="lead">
                Creado {{$user->created_at->diffForhumans()}}
            </p>
            <p class="lead">
                Correo: {{$user->email}}
            </p>
            <p class="lead">
                Créditos: {{$user->credits}}
            </p>
            <p class="lead">
                Anuncios: {{$user->properties()->count()}}
            </p>
        </div>
    </div>

    <div class="container">

        <form class="form-horizontal" id="form_create" action="{{ route('admin.users.update',$user) }}" method="POST" >
            @csrf
            @method('put')
            <div class="form-group row">
                <label for="credits" class="col-sm-2 col-form-label">Asignar Creditos <small class="required"></small></label>
                <div class="col-sm-10">
                    <input type="number" min="0" class="form-control" name="credits" id="credits"
                           value="{{old('credits')}}" placeholder="Para asignar crédito digite la cantidad"
                    >
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Cambiar Contraseña <small class="required"></small></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="password" id="password"
                           value="{{old('password')}}" placeholder="Para cambiar contraseña escriba una nueva."
                    >
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
@endsection
