@extends('frontend.layouts.app')

@section('content')
    <br>

    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
            {{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>{{trans('pages.credits.send.send_title')}}</h2>
                </div>

                <div class="card-body">
                    @include('partials._loading')
                    <form class="form" action="{{route('sent.credits', app()->getLocale())}}" method="post" id="sentCreditForm">
                        @csrf
                        @method('post')

                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="email"><h4></h4></label>
                                <input disabled  type="text" class="form-control" name="email" id="email" placeholder="{{trans('pages.credits.send.email_to_send')}}" title="" value="{{old('email')}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="credits"><h4></h4></label>
                                <input disabled  type="number" min="0" max="{{(int)auth()->user()->credits}}" class="form-control datePicker" name="credits" id="credits" value="" placeholder="{{trans('pages.credits.send.placeholders.credits', ['total' => auth()->user()->credits])}} " title="Ingrese su Fecha de Nacimiento">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" id="BtnSubmit"  type="submit"><i class="glyphicon glyphicon-ok-sign"></i> {{trans('pages.credits.send.submit_button')}}</button>

                                {{--                                    <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Borrar</button>--}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
