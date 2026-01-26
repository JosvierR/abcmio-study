@extends('frontend.layouts.app')

@section('content')
    @if(app()->getLocale() === 'es')
        @include("partials.pages.es._term")
    @else
        @include("partials.pages.en._term")
    @endif
@endsection
