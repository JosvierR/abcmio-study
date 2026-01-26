@extends('frontend.layouts.app')
@section('scripts')
    <script src="{{ asset('js/search.js') }}"></script>
@stop
@section('content')
    @include('frontend.common.search' ,['searchUrl' => $searchUrl ])
{{--    @include('frontend.common.profile-search',['searchUrl' =>'search.property'])--}}
    @if(\Auth::check())
    <div class="row">

        <a href="{{route('properties.create', app()->getLocale())}}" class="btn btn-success btn-lg btn-block">
             <i class="fa fa-plus-square"></i>
            @lang('search.buttons.create_ad')
        </a>
    </div>
    @endif


{{--    New Layout--}}
    @if(request()->get('modern', false))
        @include('frontend.directories.loop-modern')
    @else
        @include('frontend.directories.loop')
    @endif
{{--    end New Layout--}}

    @include('partials._scroll_top')
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.openModalReport').click(function(e) {
                e.preventDefault();
                $('#reportModal').modal('show');
                $('#report-id').val($(this).data('report-id'));
            });

            $('#report-form-submit-btn').on('click', function (e) {
                e.preventDefault();
                const id = $('#report-option').data('property');

                const data = $('#report-form').serialize();
                window.axios.post('{{route('reports.store',app()->getLocale())}}', data).then((res) => {
                    const {success, msg} = res.data;


                    if(success) {
                        $('p#report-id-'+id).hide();
                        $('#reportModal').modal('hide');
                        $('#success-message').html(msg);
                        $('#showMessage').modal('show');
                    }else {
                        $('#error-message').html(msg);
                        $('#reportModal').modal('hide');
                        $('#errorMessage').modal('show');
                    }
                });
            })
        });

        $(document).ready(function () {

            $('.counter').each(function () {
                $(this).prop('Counter', 0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 4000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });

        });
    </script>
@endpush

@push('modals')
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reportar Anuncio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="report-form" >
                        @csrf
                        @method('POST')
                        <fieldset>
                            <input type="hidden" name="report-id" id="report-id" value="-1">
                            <select name="report-option" id="report-option" data-property="">
                                @foreach($options ?? [] as $option)
                                    <option value="{{$option->id}}">{{$option->name}}</option>
                                @endforeach
                            </select>
                            {{--                            <textarea name="report-content" placeholder=""></textarea>--}}
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" id="report-form-submit-btn" class="btn btn-primary">Enviar Reporte</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="successMessage" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="alert alert-success" id="success-message">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="errorMessage" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="alert alert-danger" id="error-message">

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endpush




@push('styles')
    <style>
        /*.counter-section i {*/
        /*    display: block;*/
        /*    margin: 0 0 10px*/
        /*}*/
        .counter-section {
            font-size: 18px;
        }
        /*.counter-section span.counter {*/
        /*    font-size: 40px;*/
        /*    color: #000;*/
        /*    line-height: 60px;*/
        /*    display: block;*/
        /*    font-family: "Oswald", sans-serif;*/
        /*    letter-spacing: 2px*/
        /*}*/

        /*.counter-title {*/
        /*    font-size: 12px;*/
        /*    letter-spacing: 2px;*/
        /*    text-transform: uppercase*/
        /*}*/

        /*.counter-icon {*/
        /*    top: 25px;*/
        /*    position: relative*/
        /*}*/

        /*.counter-style2 .counter-title {*/
        /*    letter-spacing: 0.55px;*/
        /*    float: left;*/
        /*}*/

        /*.counter-style2 span.counter {*/
        /*    letter-spacing: 0.55px;*/
        /*    float: left;*/
        /*    margin-right: 10px;*/
        /*}*/

        /*.counter-style2 i {*/
        /*    float: right;*/
        /*    line-height: 26px;*/
        /*    margin: 0 10px 0 0*/
        /*}*/

        .counter-subheadline span {
            float: right;
        }

        /*.medium-icon {*/
        /*    font-size: 20px !important;*/
        /*    margin-bottom: 15px !important;*/
        /*}*/

        /*.container{*/
        /*    margin-top:200px;*/
        /*}*/


    </style>
@endpush
