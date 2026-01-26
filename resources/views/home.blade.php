@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <h3>{{\App\Property::where('is_public',true)->count()}}</h3>
                    <table class="table datatable">
                        @foreach($properties as $property)
                        <tr>
                            <td>{{$property->title}}</td>
                            <td>@if($property->city) {{$property->city->name}} @endif</td>
                            <td>{{$property->category->name}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
