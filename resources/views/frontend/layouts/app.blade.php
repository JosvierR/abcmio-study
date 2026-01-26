<?php
$random = \Str::random(8); ?>
        <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ABCMio ') }} - {{  $property->title ?? 'ABCMio'}} </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css?ver='.$random) }}" rel="stylesheet">

    <meta property="og:url"
          content="@if(isset($property)) https://abcmio.com/{{$property->slug}} @else https://abcmio.com @endif "/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="ABCMIO @if(isset($property)) - {{$property->title}} @endif"/>
    {{--    <meta property="og:description"   content="@if(isset($property)) - {{$property->description}} @else Publica lo que quieras @endif" />--}}
    @if(isset($property))
        <meta property="og:image" content="{{\Storage::url($property->image_path)}}"/>
    @else
        <meta property="og:image" content="{{asset('custom/img/logo.png')}}"/>
@endif
@include("frontend.shared._styles")
@yield('styles')
@stack('styles')

<!-- Styles -->
    <link href="{{ asset('custom/css/layouts.css?ver='.$random) }}" rel="stylesheet">
    @if(request()->get('modern', false))
        <link href="{{ asset('css/modern-layouts.css?ver='.$random) }}" rel="stylesheet">
    @endif
{{--    <link href="{{ asset('css/styles.css?ver='.$random) }}" rel="stylesheet">--}}
    <!-- Swet Alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{--    <script src="sweetalert2.min.js"></script>--}}
{{--    <link rel="stylesheet" href="sweetalert2.min.css">--}}
    <!-- Awesomen Fonts -->
    <script src="https://kit.fontawesome.com/f5803b0cc2.js" crossorigin="anonymous"></script>
    <!-- Google tag (gtag.js) -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DK3X257EDV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-DK3X257EDV');
    </script>
</head>
<body>
{{--{!! NoCaptcha::renderJs() !!}--}}
<div id="app">
    @include('frontend.shared._header')
    <main class="py-4">
        @yield('breadcrumbs')
        @include('frontend.shared._notifications')
            <div class="container">
                @yield('content')
            </div>
    </main>

    @include('frontend.shared._footer')
</div><!--#app-->


<script src="{{ asset('js/app.js?ver='.$random) }}"></script>

@include("frontend.shared._scripts")
@yield('scripts')
@stack('scripts')
@stack('modals')

<!-- Scripts -->
<script src="{{ asset('custom/js/scripts.js?ver='.$random) }}"></script>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>
