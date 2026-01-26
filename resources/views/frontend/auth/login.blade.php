@extends('frontend.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @include('partials._loading')

                <div class="card-body">
                    <form method="POST" action="{{ route('login', app()->getLocale()) }}">
                        @csrf
                        <div class="form-group row">

                            <label for="email" class="col-md-4 col-form-label text-md-right">
{{--                                {{ __('Correo Electrónico') }}--}}
                            </label>

                            <div class="col-md-6">
                                <input id="email" disabled type="email"
                                       placeholder="{{trans('global.login_email')}}"
                                       class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                <input disabled id="password"
                                       placeholder="{{trans('global.login_password')}}"
                                       type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row d-none d-sm-block">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{trans('global.remember_me')}}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0 ">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('global.login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link d-none" href="{{ route('password.request', app()->getLocale()) }}">
                                        {{ trans('global.forgot_password') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

