@extends('frontend.layouts.app')
@section('breadcrumbs')
@stop
@push('styles')
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>--}}
@endpush
@push("scripts")
    {{--    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>--}}
    <script scr="{{asset("js/messages.js")}}"></script>
@endpush
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="d-flex flex-row">
                @if($property->IsOwner)
                    <div class="p-2">
                        <a href="{{route('home', app()->getLocale())}}" class="btn btn-primary">Directorio</a>
                    </div>
                    <div class="p-2">
                        <a href="{{route('properties.index', app()->getLocale())}}" class="btn btn-danger">Mis
                            Anuncios</a>
                    </div>
                    <div class="p-2">
                        @if(isset($property))
                            <a href="{{route('properties.edit', [app()->getLocale(),$property])}}"
                               class="btn btn-success">Editar</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('frontend.properties._counter')


    <div class="row justify-content-center" id="productDetail">
        <div class="col-md-8">
            <article id="property">
                <div id="image_preview">
                    {{--                    @if(!empty(trim($property->image_path)))--}}
                    {{--                        <img src="{{\Storage::url($property->image_path)}}" alt="{{\Storage::url($property->image_path)}}"/>--}}
{{--                    @if(!$property->getMedia("photo")->isEmpty())--}}
{{--                        <img src="{{$property->getMedia("photo")->first()->getUrl("large")}}"--}}
{{--                             alt="{{$property->ThumbImage}}"/>--}}
{{--                    @else--}}
{{--                        <img src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}"/>--}}
{{--                    @endif--}}
                    {{--                    @endif--}}
                </div>
                <div class="propid">
                    {{$property->id ?? 'N/A'}}
                </div>

                <div class="clearFix"></div>

                @if(isset($property) && $property->getMedia("gallery")->isNotEmpty())
                    <div class="form-group row">
                        <div id="property-gallery" class="d-none">
                            @foreach($property->getMedia("gallery") as $photo)
                                <a class="property-links photo_link" href="#" data-url="{{$photo->getUrl("large")}}"
                                   data-fancybox="gallery">
                                    <div class="photo">
                                        <img src="{{$photo->getUrl("thumb")}}" alt="{{$photo->getUrl("thumb")}}"
                                             class="thumb"/>
                                    </div>
                                </a>
                            @endforeach
                            <div class="clearFix"></div>
                        </div>
                    </div>
                @endif

                <h1 class="d-flex justify-content-start">{{$property->title}}</h1>
                {{--                <h4> @if($property->category){{($property->category->parent)?$property->category->parent->name." / ":''}}  {{$property->category->name}}@endif</h4>--}}
                {{--                <h5>@if($property->city){{$property->city->name}}/ {{$property->city->country->name}}@endif</h5>--}}

                <div class="d-flex bd-highlight mb-1 justify-content-start">
                    <div class=" bd-highlight justify-content-center">
                        @if(!empty($property->website))
                            <p class="website justify-content-center"><a class="justify-content-center property-links"
                                                                         target="_blank" data-toggle="tooltip"
                                                                         data-placement="top"
                                                                         title="Hacer click para abrir enlace"
                                                                         href="{{$property->website}}"> Visitar Sitio
                                    Web </a></p>
                        @endif
                    </div>
                    {{--                    <div class="p-2 bd-highlight">--}}
                    {{--                        Creado el <strong>{{$property->created_at->format('d/m/Y')}}</strong>--}}
                    {{--                    </div>--}}
                </div>

                <div class="d-flex flex-row justify-content-start">
                    @if(!empty($property->address))
                        <div class="justify-content-center">
                            <p class="address"><strong>{{$property->address}}</strong></p>
                        </div>
                    @endif
                </div>


                <div class="d-flex bd-highlight mb-1 justify-content-start">
                    <div class="  bd-highlight ">
                        @if(!empty($property->google_map))
                            <a target="_blank" class="property-links" href="{{$property->google_map}}"
                               data-toggle="tooltip" data-placement="top" title="Abrir en Google Map">
                                <p class="googlemap">Geo Localización</p>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="d-flex bd-highlight mb-1 justify-content-start">
                    <div class="  bd-highlight">
                        @if(!empty($property->phone))
                            <p class="phone"><strong><a class="property-links" href="tel:{{$property->phone}}"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="Click para llamar desde móbil">{{$property->phone}}</a></strong>
                            </p>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-row justify-content-start">
                    @if(!empty($property->email) )
                        <div class="justify-content-center">
                            <p><strong><a class="property-links" href="mailto:{{$property->email}}"
                                          data-toggle="tooltip" data-placement="top"
                                          title="Hacer click para enviar correo">{{$property->email}}</a></strong></p>
                        </div>
                    @endif

                </div>
                <div class="d-flex flex-row justify-content-start">
                    @if(!empty($property->whatsapp_number) )
                        <div class="justify-content-center">
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
                    @endif

                </div>
                <p>
                    {!!$property->description!!}
                </p>
                <div class="d-flex flex-row-reverse">

                    <div class="p-2">
                        {{--                !$property->IsOwner &&--}}

                        @if( (int)$property->send_message)
                            {{--                            <p >--}}
                            {{--                                <a  href="#" data-toggle="tooltip" data-placement="top" title="Enviar mensaje a propietario">--}}
                            {{--                                    Enviar mensaje <i class="fas fa-comment-alt"></i>--}}
                            {{--                                </a>--}}
                            {{--                            </p>--}}

                        @endif
                        @if(!is_null($property->isPublic()) && auth()->check() && in_array(auth()->user()->type, ['admin', 'super']))
                            <a href="{{route("admin.privating.store",[app()->getLocale(), $property])}}" class="btn btn-warning btn-sm">{{trans('pages.my_ads.info_table.buttons.private')}}</a>
                        @endif
                    </div>

                    <div class="p-2">
                        <p><a href="#" class="property-links" data-toggle="modal" data-target="#reportModal" data-placement="top"
                              title="Denunciar Anuncio">{{trans('properties.report_ad')}} <i class="fas fa-exclamation-triangle"></i></a></p>
                    </div>
                </div>
                @if((int)$property->send_message && \Auth::check())
                    {{--                <div class="row">--}}
                    {{--                    <div class="col-md-12">--}}
                    {{--                        <div class="contact-form_container">--}}
                    {{--                            <div class="form-header">--}}
                    {{--                                @if(!empty($property->show_email))--}}
                    {{--                                <i class="fa fa-user"></i>--}}
                    {{--                                <h3>{{$property->user->name ?? ''}}</h3>--}}
                    {{--                                <span>Por {{$property->user->email ?? ''}}</span>--}}
                    {{--                                @endif--}}
                    {{--                            </div>--}}
                    {{--                            <div class="cta_container">--}}
                    {{--                                    <span>Enviar Mensaje al Anunciante</span>--}}
                    {{--                            </div>--}}
                    {{--                            <form action="{{route("send.product.message", [app()->getLocale(), $property])}}" method="POST" id="send-email-form" >--}}
                    {{--                                @csrf--}}
                    {{--                                @method("POST")--}}
                    {{--                                <div class="form-group">--}}
                    {{--                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nombre" value="{{Auth::user()->name ?? ''}}">--}}
                    {{--                                </div>--}}
                    {{--                                <div class="form-group">--}}
                    {{--                                    <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" value="{{Auth::user()->email ?? ''}}">--}}
                    {{--                                </div>--}}
                    {{--                                <div class="form-group">--}}
                    {{--                                    <input type="number" name="phone" id="phone" class="form-control" placeholder="Número telefónico" value="">--}}
                    {{--                                </div>--}}
                    {{--                                <div class="form-group">--}}
                    {{--                                    <textarea class="form-control" name="message" id="message" placeholder="Mensaje" rows="3"></textarea>--}}
                    {{--                                </div>--}}
                    {{--                                <button type="submit" class="btn btn-block">Enviar Mensaje</button>--}}
                    {{--                            </form>--}}
                    {{--                            --}}{{--                Envio de mensaje--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                @endif

            </article>
        </div>
    </div>


    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Denunciar Anuncio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('reports.store', app()->getLocale())}}" method="POST" id="report-form">
                        @csrf
                        @method('POST')
                        <select name="report-option" id="report-option" class="form-control">
                            @foreach($reportOptions ?? [] as $reportOption)
                                <option value="{{$reportOption->id}}">{{$reportOption->name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="report-id" value="{{$property->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" id="reportAd" class="btn btn-primary">Denunciar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <script>
        $(function () {
            $('.photo_link').click(function (e) {
                e.preventDefault();
                const url = $(this).data('url');
                $("#image_preview").find('img').attr('src', url);
            });


            $('#reportAd').on('click', function (e) {
                e.preventDefault();
                const id = $('#report-option').data('property');

                const data = $('#report-form').serialize();
                window.axios.post('{{route('reports.store',app()->getLocale())}}', data).then((res) => {
                    const {success, msg} = res.data;
                    $('#reportModal').modal('hide');
                    if(success) {
                        // $('p#report-id-'+id).hide();
                        $('#reportModal').modal('hide');
                        // $('#success-message').html(msg);
                        // $('#showMessage').modal('show');+
                        Swal.fire({
                            title: 'Este anuncio fue reportado',
                            text: msg,
                            icon: 'success',
                            // showDenyButton: false,
                            // showCancelButton: true,
                            // confirmButtonText: 'Si',
                            // cancelButtonText: 'No',
                            // denyButtonText: `No borrar`,
                        })
                    }else {
                        // $('#error-message').html(msg);
                        $('#reportModal').modal('hide');
                        // $('#errorMessage').modal('show');

                        Swal.fire({
                            title: 'Importante',
                            text: msg,
                            icon: 'warning',
                            // showDenyButton: false,
                            // showCancelButton: true,
                            // confirmButtonText: 'Si',
                            // cancelButtonText: 'No',
                            // denyButtonText: `No borrar`,
                        })
                    }
                });
            })


            // $("#reportAd").on('click', function() {
            //     const form = $("#report-form").serialize();
            //     axios.post('')
            // });
        });



    </script>
@endpush




