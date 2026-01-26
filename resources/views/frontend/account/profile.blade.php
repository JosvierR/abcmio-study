@extends('frontend.layouts.app')

@section('scripts')
    <script src="{{asset('js/profile.js')}}"></script>
@stop

@section('content')
    <div class="container bootstrap snippet">
        <div class="row">
            <div class="col-sm-10"><h1>{{trans('pages.profile.header.title')}}</h1></div>
        </div>
        <div class="row">
            <div class="col-sm-3"><!--left col-->
                <div class="panel panel-default">
                    <div class="panel-heading">{{trans('pages.profile.header.email')}} <i class="fa fa-link fa-1x"></i></div>
                    <div class="panel-body">{{\Auth::user()->email}}</div>
                </div>
            </div><!--/col-3-->
            <div class="col-sm-9">
                <div class="tab-content">
                    @include('partials._loading')
                    <div class="tab-pane active" id="home">
                        <hr>
                        <form class="form" action="{{route('profile.update', app()->getLocale())}}" method="post" id="updateProfile">
                            @csrf
                            @method('post')

                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="name"><h4>{{trans('pages.profile.form.name.title')}}</h4></label>
                                    <input disabled type="text" class="form-control" name="name" id="name"
                                           placeholder="{{trans('pages.profile.form.name.placeholder')}}" title="enter your first name if any."
                                           value="{{$user->name}}">
                                </div>
                            </div>

                            <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="country_id"><h4>{{trans('pages.profile.form.country.title')}} <small class="required">*</small></h4>
                                            <select name="country" id="country" class=" form-control"
                                                    data-live-search="true" >
                                                @foreach(LocationRepository::getCountries() ?? [] as $country)
                                                    <option value="{{$country->id}}"
                                                            @if(isset($countryId)&& $countryId==$country->id) selected @endif>
                                                        {{$country->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                </div>

                            <div class="form-group">

                                <div class="col-xs-6">
                                    <label for="birth_date"><h4>{{trans('pages.profile.form.birth_date.title')}}</h4></label>
                                    <input disabled type="text" class="form-control datePicker" name="birth_date"
                                           id="birth_date"
                                           value="{{Carbon\Carbon::parse($user->birth_date)->format('d/m/Y')}}"
                                           placeholder="13/06/1996" title="{{trans('pages.profile.form.birth_date.placeholder')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gender"><h4>{{trans('pages.profile.form.gender.title')}}</h4></label>
                                <div class="form-check">
                                    <input disabled class="form-check-input" type="radio" name="gender" id="genderMale"
                                           value="male" checked>
                                    <label class="form-check-label" for="genderMale">
                                        {{trans('pages.profile.form.gender.options.male')}}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input disabled class="form-check-input" type="radio" name="gender"
                                           id="genderFemale" value="female">
                                    <label class="form-check-label" for="genderFemale">
                                        {{trans('pages.profile.form.gender.options.female')}}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="password"><h4>{{trans('global.change_password')}} <small></small></h4></label>
                                    <input type="password" class="form-control" name="current_password" id="password"
                                           placeholder="{{trans('global.current_password')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <input type="password" class="form-control" name="new_password" id="password"
                                           placeholder="{{trans('global.new_password')}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <input type="password" class="form-control" name="new_confirm_password"
                                           id="password-confirmation" placeholder="{{trans('global.confirm_password')}}">
                                </div>
                            </div>
                            @if(!$user->confirmed)
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="termandcondition">Términos y Condiciones </label>
                                        {{--                                        <textarea class="form-control" id="termandcondition" rows="10" readonly>--}}
                                        <div id="term_and_condition">
                                            @include("partials.pages.es._term")
                                        </div>
                                        {{--                                        </textarea>--}}
                                    </div>
                                </div>
                                <div class="form-group form-check">
                                    <input disabled type="checkbox" class="form-check-input" id="confirmed"
                                           name="confirmed" value="1">
                                    <label class="form-check-label" for="confirmed">Acepto Términos y
                                        Condiciones</label>
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <br>
                                    <button class="btn btn-lg btn-success" id="BtnSubmit"
                                            @if(!$user->confirmed) disabled @endif type="submit"><i
                                                class="glyphicon glyphicon-ok-sign"></i> Guardar
                                    </button>
                                    @if($user->confirmed)
                                        <button class="btn btn-lg btn-danger" type="button"><i
                                                    class="glyphicon glyphicon-ok-sign"></i> Salir
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <hr>
                    </div><!--/tab-pane-->
                </div><!--/tab-pane-->
            </div><!--/tab-content-->
        </div><!--/col-9-->
    </div><!--/row-->
@endsection
