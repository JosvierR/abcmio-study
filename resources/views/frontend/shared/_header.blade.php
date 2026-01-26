<nav class="navbar navbar-expand-md navbar-light navbar-laravel" id="main-menu">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/', app()->getLocale()) }}" id="site-logo">
            <img src="{{asset('custom/img/logo.jpg')}}" alt="">
            <span class="ml-4">{{$site_section_name ?? ''}}</span><br>
            <span class="ml-4 mt-0 pt-0">{{ trans('nav.header.nav.by_city') }}</span><br>
        </a>
{{--        <button class="navbar-toggler"--}}
{{--                type="button"--}}
{{--                data-toggle="collapse"--}}
{{--                data-target="#navbarSupportedContent"--}}
{{--                aria-controls="navbarSupportedContent"--}}
{{--                aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}

{{--        Menu open with show class--}}
        <div class="collapse navbar-collapse show" id="navbarSupportedContent">
{{--        <div class="collapse navbar-collapse" id="navbarSupportedContent">--}}
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto"></ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto" id="menu-desktop">
                <!-- Authentication Links -->

                @include('partials.nav.language')
                @guest
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('login', app()->getLocale()) }}">{{ trans('nav.header.nav.login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link"
                               href="{{ route('register', app()->getLocale()) }}">{{ trans('nav.header.nav.register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-circle" style="font-size: 18px"></i>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @auth
                                <a class="dropdown-item" href="{{route('profile', app()->getLocale())}}">
                                    <i class="fa fa-user"></i> {{trans('global.admin.profile.title_singular')}}
                                </a>
                                @if(\Auth::user()->type=='admin' || \Auth::user()->type=='super')
                                    <a class="dropdown-item" href="{{route('admin.categories.index' )}}">
                                        <i class="fa fa-list"></i> {{trans('global.admin.category.title')}}
                                        <span class="badge badge-pill badge-success">{{\App\Category::where('parent_id',0)->count()}}</span>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.countries.index')}}">
                                        <i class="fa fa-list"></i>
                                        {{trans('global.admin.country.title')}}
                                        <span class="badge badge-pill badge-success">  {{\App\Country::count()}}</span>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.users.index')}}">
                                        <i class="fa fa-users"></i>
                                        {{trans('global.admin.user.title')}}
                                        <span class="badge badge-pill badge-success">  {{\App\User::count()}}</span>
                                    </a>
                                    <a class="dropdown-item" href="{{route('admin.credits.index')}}">
                                        <i class="fas fa-money-check-alt"></i>
                                        {{trans('nav.header.nav.admin.credit')}}
                                        <span class="badge badge-pill badge-success">  {{\App\Credit::count()}}</span>
                                    </a>
                                    <a href="{{route('reports.index', app()->getLocale())}}" class="dropdown-item">
                                        <i class="fas fa-list"></i> {{trans('nav.header.nav.reports')}}
                                        <span class="badge badge-pill badge-danger">  {{$total_report ?? 0}}</span>
                                    </a>
                                @endif

                                <a href="{{route('home', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fas fa-list"></i> {{trans('nav.header.nav.directory')}}
                                </a>

                                <a href="{{route('properties.index', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fa fa-home"></i>
                                    {{trans('nav.header.nav.my-ads')}}
{{--                                    <span class="badge badge-pill badge-success">  {{\Auth::user()->properties->count()}}</span>--}}
                                </a>
                                <a href="{{route('properties.create', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fa fa-plus"></i> {{trans('nav.header.nav.add-ad')}}
                                </a>
                                <a href="{{route('credits.index', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    {{trans('nav.header.nav.buy-credits')}}
                                    <span class="badge badge-pill badge-primary">  {{auth()->user()->credits}}</span>
                                </a>
                                <a href="{{route('send.credits', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    {{trans('nav.header.nav.sell-credits')}}
                                </a>
                                {{--                            <a href="#" class="dropdown-item">--}}
                                {{--                                <i class="fas fa-comments"></i> Mensajes <span class="badge badge-pill badge-danger"> <i class="fas fa-envelope"></i> 1</span>--}}
                                {{--                            </a>--}}
                                @if(\Auth::user()->type == 'super' || \Auth::user()->type == 'admin')
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs"></i>
                                        {{trans('nav.header.nav.admin.title')}}
                                    </a>
                                @endif
                            @endif
                            <a class="dropdown-item" href="{{ route('logout', app()->getLocale()) }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ trans('global.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout', app()->getLocale()) }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
            <ul class="navbar-nav ml-auto " id="menu-mobile">
                @include('partials.nav.language-horizontal' )
                @guest
                    <div id="login-menu" class="d-flex justify-content-center flex-row">
                        <div class="pr-5 "><a class="nav-link {{request()->is(app()->getLocale() . '/login') ? 'active' : ''}}"
                                href="{{ route('login', app()->getLocale()) }}">{{trans('nav.header.nav.login')}}</a>
                        </div>
                        @if (Route::has('register'))
                            <div class=" pl-5"><a class="nav-link {{request()->is(app()->getLocale() . '/register') ? 'active' : ''}}"
                                    href="{{ route('register', app()->getLocale()) }}">{{trans('nav.header.nav.register')}}</a>
                            </div>
                        @endif
                    </div>
                @else
                    <li class="nav-item">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="dropdown-item" href="{{route('profile', app()->getLocale())}}">
                                <i class="fa fa-user"></i> {{trans('global.admin.profile.title_singular')}}
                            </a>
                        </li>
                        @if(\Auth::user()->type=='admin' || \Auth::user()->type=='super')
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{route('admin.categories.index')}}">
                                    <i class="fa fa-list"></i> {{trans('global.admin.category.title')}} <span
                                            class="badge badge-pill badge-success">  {{\App\Category::where('parent_id',0)->count()}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{route('admin.countries.index')}}">
                                    <i class="fa fa-list"></i>
                                    {{trans('global.admin.country.title')}} <span
                                            class="badge badge-pill badge-success">  {{\App\Country::count()}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{route('admin.users.index')}}">
                                    <i class="fa fa-users"></i>
                                    {{trans('global.admin.user.title')}} <span
                                            class="badge badge-pill badge-success">  {{\App\User::count()}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="dropdown-item" href="{{route('admin.credits.index', app()->getLocale())}}">
                                    <i class="fas fa-money-check-alt"></i>
                                    {{trans('nav.header.nav.admin.credit')}} <span
                                            class="badge badge-pill badge-success">  {{\App\Credit::count()}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('reports.index', app()->getLocale())}}" class="dropdown-item">
                                    <i class="fas fa-list"></i> {{trans('nav.header.nav.reports')}}
                                    <span class="badge badge-pill badge-danger">  {{$total_report ?? 0}}</span>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('home', app()->getLocale())}}" class="dropdown-item">
                                <i class="fas fa-list"></i> {{trans('nav.header.nav.directory')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('properties.index' , app()->getLocale())}}" class="dropdown-item">
                                <i class="fa fa-home"></i> {{trans('nav.header.nav.my-ads')}}
{{--                                <span--}}
{{--                                        class="badge badge-pill badge-success">--}}
{{--                                    {{\Auth::user()->properties->count()}}--}}
                                </span>
                            </a>
                        </li>
                        <a href="{{route('properties.create', app()->getLocale())}}" class="dropdown-item">
                            <i class="fa fa-plus"></i> {{trans('nav.header.nav.add-ad')}}
                        </a>
                        <li class="nav-item">
                            <a href="{{route('credits.index' , app()->getLocale())}}" class="dropdown-item">
                                <i class="fas fa-dollar-sign"></i> {{trans('nav.header.nav.buy-credits')}}
                                <span class="badge badge-pill badge-primary">  {{auth()->user()->credits}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('send.credits', app()->getLocale())}}" class="dropdown-item">
                                <i class="fas fa-dollar-sign"></i> {{trans('nav.header.nav.sell-credits')}}
                            </a>
                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a href="#" class="dropdown-item">--}}
{{--                                <i class="fas fa-comments"></i> Mensajes--}}
{{--                                <span class="badge badge-pill badge-danger">--}}
{{--                                <i class="fas fa-envelope"></i> 1--}}
{{--                            </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}
                    @endauth
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('logout', app()->getLocale()) }}"
                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            {{ trans('global.logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout', app()->getLocale()) }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
