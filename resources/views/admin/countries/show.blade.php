@extends('frontend.layouts.app')
@section('scripts')
    <script src="{{asset('js/categories.js')}}"></script>
@stop
@section('styles')
@stop
@section('content')

    <div class="row justify-content-start">
        <section class="col-12 ">
            <form action="{{route("admin.city.search",$country)}}" method="POST" id="search-form">
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

    <div class="form-group row">
        <a href="{{route('admin.cities.create',$country)}}" class="btn btn-primary"><i class="fa fa-plus"></i> {{trans('global.add')}} {{trans('global.admin.city.title_singular')}}</a>
    </div>
    <br>
    <div class="row">
        <a href="{{route('admin.countries.index')}}" class="btn btn-danger">
            {{trans('global.back')}}
        </a>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
{{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>País: <a href="{{route('admin.countries.index')}}">{{$country->name}}</a> con {{$country->cities->count()}} ciudades</h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{$cities->links()}}
                    </div>
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable" id="country_table">
                            <thead>
                            <tr>
                                <th >
                                    Nombre
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cities as $city)
                                <tr  >
                                    <td>
                                        <a href="{{route('admin.cities.edit',$city)}}"><h3 class="text-dark">{{$city->name}}</h3></a>
                                    </td>
                                    <td>
                                        <div class="trash">
                                            <form action="{{route('admin.cities.destroy',$city)}}" method="POST" class="act-delete">
                                                @method('DELETE')
                                                @csrf
                                                {{--                                        <a href="{{route('admin.categories.edit',$category)}}" class="action-btn">--}}
                                                {{--                                            <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="Editar Categoría"></i>--}}
                                                {{--                                        </a>--}}
                                                <button type="submit" class="btn action action-btn">
                                                    <i class="fas fa-trash trash" data-toggle="tooltip" data-placement="top" title="Borrar: {{$city->name}} "></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="row">
                        {{$cities->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
