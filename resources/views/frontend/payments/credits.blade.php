@extends('frontend.layouts.app')

@section('content')

    <ul>
    @foreach($credits as $credit)
        <li>
            <a href="">{{$credit->name}} - </a>
        </li>
    @endforeach
    </ul>
@endsection
