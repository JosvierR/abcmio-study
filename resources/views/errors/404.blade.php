@extends('frontend.layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="error_page" class="error_404">
                <h2>{{trans('global.error_page_not_found')}}</h2>
            </div>
        </div>
    </div>
@endsection
