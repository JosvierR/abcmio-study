@extends('frontend.layouts.app')
@section('scripts')
@stop
@section('styles')
@stop
@section('content')
    <div class="form-group row">
        <a href="{{route('admin.credits.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> {{trans('global.add')}} Creditos</a>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
            {{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>Comprar Días:</h2>
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
                            @foreach($credits as $credit)
                                <tr  >
                                    <td>
                                        <a href="{{route('admin.credits.edit',$credit)}}"><h3 class="text-dark">{{$credit->name}}</h3></a>
                                    </td>
                                    <td>
                                        {{$credit->total}}
                                    </td>
                                    <td>
                                        {{$credit->TotalPrice}}
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
