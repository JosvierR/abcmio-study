@extends('frontend.layouts.app')
@section('scripts')
    <script src="{{asset('js/categories.js')}}"></script>
@stop
@section('styles')
@stop
@section('content')
    <div class="form-group row">
        <a href="{{route('admin.cities.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> {{trans('global.add')}} {{trans('global.admin.city.title_singular')}}</a>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
            {{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>País: <a href="{{route('admin.countries.index')}}">{{$country->name}}</a> con {{$country->cities->count()}} ciudades</h2>
                </div>

                <div class="card-body">
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
                            @foreach($country->cities as $city)
                                <tr  >
                                    <td>
                                        <a href="{{route('admin.cities.edit',$city)}}"><h3 class="text-dark">{{$city->name}}</h3></a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
