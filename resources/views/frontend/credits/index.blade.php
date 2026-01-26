@extends('frontend.layouts.app')

@section('content')
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12 col-12">
            {{--            <country-index></country-index>--}}
            <div class="card">
                <div class="card-header">
                    <h2>{{trans('pages.credits.option_label_header', ['total' =>  $credits->count()])}}</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover datatable" id="country_table" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center"><span class="font-weight-bold">{{trans('pages.credits.title')}}</span></th>
                                <th class="text-center"><span class="font-weight-bold">{{trans('pages.credits.total_title')}}</span></th>
                                <th class="text-center"><span class="font-weight-bold">{{trans('pages.credits.option_method')}}</span></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($credits as $credit)
                                <tr>
                                <td class="text-center"><span class="font-weight-bold">{{$credit->total}}</span></td>
                                <td class="text-center"><span class="font-weight-bold">{{$credit->TotalPrice}}</span></td>
                                <td class="text-center">
                                    <a href="{{route('paypal.form',[app()->getLocale(), $credit])}}" class="btn btn-primary paypal" data-toggle="tooltip" data-placement="top" title="Comprar ahora">
                                        <i class="fab fa-paypal"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="justify-content-center text-center">
                            <a href="https://www.paypal.com/donate/?hosted_button_id=GMXJL5FCSGK7U">
                                <img class="text-center" src="{{asset('images/donate.png')}}" alt="" width="100">
                            </a>
                        </div>
                    </div>
                </div>
                <h3 class="text-center mt-3">{{trans('pages.credits.option_method_others')}}</h3>
                <div class="form-group">
                    <div class="col-12 mt-4 mb-3 btns">
                        <a href="#" class="btn btn-success" id="btn-cuba">
                            <i class="fas fa-shopping-cart" ></i> Cuba
                        </a>
                        <a href="#" class="btn btn-success" id="btn-haiti">
                            <i class="fas fa-shopping-cart"></i> Haiti
                        </a>
                        <a href="#" class="btn btn-success" id="btn-rd">
                            <i class="fas fa-shopping-cart"></i> R. D.
                        </a>
                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <section id="haiti" class="hide">
                            <h3 class="p-3">Para comprar días de publicidad en Haití</h3>
                            <p class="p-3 ml-3 alert alert-info">
                                <span>Depositar en Banco BUH, cuenta ahorros</span> <strong> 1800 0001364 (US$)</strong>, enviar comprobante de depósito al:
                                <div class="row justify-content-center">
                                <div class=" col">
                                        <span class="logo-icon">
                                            <strong><a href="https://wa.me/18297220976" target="_blank"><img src="{{asset('images/mobile/ws-logo.png')}}"
                                                                                             alt="">+18297220976</a></strong>
                                        </span>

                                </div>

                            </div>
                            </p>
                        </section>
                        <section id="rd" class="hide alert-info">
                            <h3 class="p-3">Para comprar días de publicidad en República Dominicana</h3>
                            <p class="p-3 ml-3 alert ">
                                <span>Depositar en Banreservas, cuenta corriente </span> <strong>1250012705 (RD$)</strong>, enviar comprobante de depósito al:

                            </p>
                            <div class="row justify-content-center">
                                <div class=" col">
                                        <span class="logo-icon">
                                            <strong><a href="https://wa.me/18294513764" target="_blank"><img src="{{asset('images/mobile/ws-logo.png')}}"
                                                                                             alt="">18294513764</a></strong>
                                        </span>

                                </div>

                            </div>
                        </section>
                        <section id="cuba" class="hide">
                            <h3 class="p-3">Para comprar días de publicidad en Cuba</h3>
                            <div class="alert-info">
                            <p class="p-3 ml-3 alert ">
                                <span>Depositar en Banco Banmet, cuenta ahorro </span> <strong>9205959873603105 (CUP)</strong>, enviar comprobante de depósito al:
                            </p>
                                <div class="row justify-content-center">
                                    <div class=" col">
                                        <span class="logo-icon">
                                            <strong><a href="https://wa.me/5354819684" target="_blank">
                                                    <img src="{{asset('images/mobile/ws-logo.png')}}"
                                                         alt="">+53 5 4819684</a></strong>
                                        </span>

                                    </div>
                                    <div class=" col">
                                        <span class="logo-icon icon-telegram">
                                            <a href="https://t.me/simbiosissurl" target="_blank">
                                                <img src="{{asset('images/mobile/telegram-logo.svg.png')}}" alt=""/> @simbiosissurl
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('#btn-rd').click(function(e) {
                e.preventDefault();
                $("#rd").show();
                $("#cuba").hide();
                $("#haiti").hide();
            });

            $('#btn-haiti').click(function(e) {
                e.preventDefault();
                $("#rd").hide();
                $("#cuba").hide();
                $("#haiti").show();
            });
            $('#btn-cuba').click(function(e) {
                e.preventDefault();
                $("#rd").hide();
                $("#haiti").hide();
                $("#cuba").show();
            });
        })
    </script>
@endpush

@push('styles')
    <style>
        section p {
            font-size: 18px;
            color: #333 !important;

        }
        section p span {}
        section p strong {
            font-weight: bolder !important;
            color: #000 !important;
        }
        span.logo-icon {}
        span.logo-icon img{ width: 50px !important;}
        span.logo-icon.icon-telegram img{ width: 35px !important;
            margin-right: 10px;}
    </style>
    @endpush