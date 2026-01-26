@extends('frontend.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ trans('global.register_title') }}</div>
                @include('partials._loading')
                <div class="card-body">
                    <form method="POST" action="{{ route('register' , app()->getLocale()) }}">

                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
{{--                                {{ __('Correo Electrónico') }}--}}
                            </label>

                            <div class="col-md-6">
                                <input disabled
                                       placeholder="{{trans('global.register_email')}}"
                                       id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
{{--                                {{ __('Contraseña') }}--}}
                            </label>

                            <div class="col-md-6">
                                <input disabled
                                       placeholder="{{trans('global.register_password')}}"
                                       id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
{{--                                {{ __('Confirmar Contraseña') }}--}}
                            </label>

                            <div class="col-md-6">
                                <input
                                        placeholder="{{trans('global.register_password_confirm')}}"
                                        disabled  id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="country_id" class="col-md-4 col-form-label text-md-right">
{{--                                {{ __('Selecciona tu País') }}--}}
                            </label>
                            <div class="col-md-6">
                                <select name="country_id" id="country_id" class=" form-control" data-live-search="true">
                                    <option value="-1">{{trans('global.register_country')}}</option>
                                    @foreach($countries ?? [] as $country)
                                        <option value="{{$country->id}}">
                                            {{$country->NameLabel}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="captcha" class="col-md-4 col-form-label text-md-right">
                                {{--                                {{ __('Selecciona tu País') }}--}}
                            </label>
                            <div class="col-md-6" id="captcha">
{{--                                {!! NoCaptcha::display() !!}--}}
{{--                                <div class="g-recaptcha" data-sitekey="6Lf1JdkqAAAAAEY92xzkK3_P_CIu5STQQgTmiMoN"></div>--}}
                                <div class="g-recaptcha" data-sitekey="6LcyLNkqAAAAAHA_r4xkMA0E7Gwpuq8zhh_WeoU5"></div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('global.register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        // $(document).ready(function(){
        //     if($("#country_id").length)
        //     {
        //         fetchStates($("#country_id").val());
        //         $("#country_id").change(function(){
        //             fetchStates($(this).val());
        //         });
        //     }
        // });

        // function fetchStates(country_id)
        // {
        //     window.axios.get('/api/cities/'+country_id).then(({data})=>
        //     {
        //         if(data.success)
        //         {
        //             $("#city_id").find('option')
        //                 .remove()
        //                 .end();
        //             $.each(data.result,function(index,value){
        //                 $("#city_id").append($("<option>",
        //                     {
        //                         value: value.id,
        //                         text:value.name
        //                     }
        //                 ));
        //             });
        //             $('#city_id option').each(function() {
        //                 if($(this).val() == $("#country_id").data("city")) {
        //                     $(this).prop("selected", true);
        //                 }
        //             });
        //         }else{
        //             alert("Error no tiene ciudades");
        //         }
        //     }).then(()=>{
        //         $("#city_id").selectpicker("refresh");
        //
        //     });
        // }
    </script>
@endpush
