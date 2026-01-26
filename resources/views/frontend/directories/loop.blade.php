<div class="row">
    <div class="col-12 col-md-8 mt-md-5 mx-auto">
        <h3 class="m-3"><small><strong>{{$properties->total()}} </strong> {{trans('properties.properties.total_found')}}</small></h3>

        <section id="directory-products">
            <nav aria-label="Page navigation example" class="d-flex m-3">
                <ul class="pagination">
                    {{$properties->appends(request()->input())->links()}}
                </ul>
            </nav>
            <ul class="mt-4">
                @foreach($properties ?? [] as $property)
                    <li
                            class="@if($property->IsOwner)owner @endif"
                    >
                        <article>
                            <a href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}">
                                <div class="info">
                                    <div class="thumb my-3">
{{--                                        <div class="propid">--}}
{{--                                            {{$property->id ?? 'N/A'}}--}}
{{--                                        </div>--}}
{{--                                        <img src="{{$property->ThumbImage}}" alt="{{$property->title}}"/>--}}
                                        <h3 class="text-break text-md-left text-center mt-3 mb-0 mx-md-4">{{$property->title}}</h3>
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-12 col-md-8">
                                                <h2 class="lower-title text-break text-center text-md-start"><strong>{{$property->business_name}}</strong></h2>
                                                <table class="table d-block col-12 sm-12">
                                                    <tr class="d-none d-sm-block">
                                                        <td>
                                                            <p class="text-break">
                                                                {{--                                                            {{$property->Excerpt ?? ''}}--}}
                                                                {!! $property->Excerpt ?? '' !!}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h4>
                                                                @if($property->category){{($property->category->parent)?$property->category->parent->name." / ":''}}  {{$property->category->name}}@endif
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td>
                                                            <h5><strong>@if($property->country){{$property->country->name}}@endif</strong></h5>
                                                        </td>
                                                    </tr>
                                                    <tr class="d-none d-sm-block">
                                                        <td>
                                                            @if($property->IsOwner)
                                                                <p class="actions">
                                                                    <a href="{{route('properties.show',[app()->getLocale(), $property])}}">
                                                                        <i class="fas fa-edit edit" data-toggle="tooltip" data-placement="top" title="Editar Anuncio"></i>
                                                                    </a>
                                                                </p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr class="d-none d-sm-block">
                                                        <td>
                                                            @if(auth()->check() && in_array(auth()->user()->type, ['super', 'admin']))
                                                                <h5>{{$property->user->email ?? 'no email'}}</h5>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            @if(!empty($property->social_network))
                                                                <div class="row">
                                                                    <div class="container d-flex d-block">
                                                                        <p>
                                                                            <i class="fa fa-planet"></i>
                                                                            <a href="{{$property->social_network}}" class="d-block">{{trans('pages.forms.ads.global.inputs.socials_media.social_network.label')}}</a>
                                                                        </p>
                                                                    </div>
                                                                </div>

                                                            @endif
                                                            @if(!empty($property->whatsapp_number) )
                                                                <div class="row">
                                                                    <div class="container">
                                                                        <div class="justify-content-center d-flex d-block">
                                                                            <p><strong><a class="property-links"
                                                                                          href="https://wa.me/{{$property->whatsapp_number}}"
                                                                                          data-toggle="tooltip"
                                                                                          data-placement="top"
                                                                                          title="Hacer click para contactar por whatsapp"
                                                                                          target="_blank"
                                                                                    >
                                                                                        <img src="{{asset('images/logo-whtsapp.png')}}" width="30" alt="">
                                                                                        {{$property->whatsapp_number}}
                                                                                    </a></strong></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            {{--                                                    <p class="message">--}}
                                                            {{--                                                        <a href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}">--}}
                                                            {{--                                                            <i class="fab fa-telegram-plane" data-toggle="tooltip" data-placement="left" title="Contactar a través de ABCmio"></i>--}}
                                                            {{--                                                        </a>--}}
                                                            {{--                                                    </p>--}}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--.info-->
                            </a>
                                    @include('frontend.properties._visitors')

                        </article>
                        <div class="clearFix"></div>
                    </li>
            @endforeach
            <!--fin de los cambios en la estructura de presentación del directorio. -->
            </ul>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    {{$properties->appends(request()->input())->links()}}
                </ul>
            </nav>
        </section>
        {{--            <section>--}}

        {{--                <ul class="pagination" role="navigation">--}}

        {{--                    <li class="page-item disabled" aria-disabled="true" aria-label="&laquo; Previous">--}}
        {{--                        <span class="page-link" aria-hidden="true">&lsaquo;</span>--}}
        {{--                    </li>--}}

        {{--                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>--}}
        {{--                    <li class="page-item"><a class="page-link" href="http://www.abcmio.com?page=2">2</a></li>--}}
        {{--                    <li class="page-item"><a class="page-link" href="http://www.abcmio.com?page=3">3</a></li>--}}

        {{--                    <li class="page-item">--}}
        {{--                        <a class="page-link" href="http://www.abcmio.com?page=2" rel="next" aria-label="Next &raquo;">&rsaquo;</a>--}}
        {{--                    </li>--}}
        {{--                </ul>--}}

        {{--            </section>--}}


    </div>
    <div class="col-md-2">
        <section class="wow fadeIn animated" style="visibility: visible; animation-name: fadeIn;">
            <div class="container">
                <div class="row">
                    <!-- counter -->
                    <div class="col-md-3 col-sm-6 text-center counter-section wow fadeInUp animated" data-wow-duration="0ms" style="visibility: visible; animation-duration: 1200ms; animation-name: fadeInUp;">
                        <i class="fa fa-user medium-icon"></i>
                        <span class="timer counter alt-font appear" data-to="600" data-speed="100">{{$visitors ?? 0}}</span>
                        <span class="counter-title">{{trans('global.visitors')}}</span>
                    </div>
                    <!-- end counter -->
                </div>
            </div>
        </section>
    </div>
</div>
