@extends('frontend.layouts.app')
@section('scripts')
@stop
@section('styles')
@stop
@section('content')
    <br>
        <div class="row justify-content-start">
            <section class="col-12 ">
                <form action="{{route("admin.country.search")}}" method="POST" id="search-form">
                @csrf
                @method('POST')
                    <fieldset class="row ">
                        <div class="row form-group">
                            <div class="col-sm-9">
                                <input class="form-control" name="query" type="text" placeholder="Búsqueda" value="{{old('query', $string ?? '')}}" />
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" name="btn_search" class="btn btn-block btn-primary">Buscar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
    <br>
    <div class="form-group row">
        <a href="{{route('admin.countries.create')}}" class="btn btn-success"><i class="fa fa-plus"></i> Crear País</a>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
{{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>{{$countries->count()}} Paises</h2>
                </div>

                <div class="card-body">
                    <div class="table-responsive">

                    @if(isset($countries))
                            <section>
                                {{$countries->links()}}
                            </section>
                        <table class=" table table-bordered table-striped table-hover datatable" id="country_table">
                            <thead>
                            <tr>
                                <th >
                                    Nombre
                                </th>
{{--                                <th>--}}
{{--                                    Total Ciudades--}}
{{--                                </th>--}}

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($countries as $country)
                                <tr  >
                                    <td>
                                        <a href="{{route('admin.countries.edit',$country)}}"><h3 class="text-dark">{{$country->name}}</h3></a>
                                    </td>
                                    <td>
{{--                                        {{$country->cities_count}}--}}
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            <section>
                                {{$countries->links()}}
                            </section>
                        @else
                            <p class="alert-warning alert">No hay paises disponibles</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
