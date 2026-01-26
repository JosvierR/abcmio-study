@extends('frontend.layouts.app')

@section('content')
        <div class="row justify-content-start">
{{--            <form action="{{route('search')}}" method="POST">--}}
{{--                @csrf--}}
{{--                @method('POST')--}}
                <advanced-search :all_countries="{{$countries}}" :parents_categories="{{$categories}}" ></advanced-search>
{{--            </form>--}}
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Directorio</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <directory-front></directory-front>
{{--                        <h3>{{\App\Property::where('is_public',true)->count()}}</h3>--}}

{{--                        <table class="table datatable">--}}

{{--                            @foreach($properties as $property)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$property->title}}</td>--}}
{{--                                    <td>@if($property->city) {{$property->city->name}} @endif</td>--}}
{{--                                    <td>{{$property->category->name}}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        </table>--}}
                    </div>
{{--                </div>--}}
            </div>
            </div>
            <div class="col-md-4">

            </div>
        </div>
@endsection
