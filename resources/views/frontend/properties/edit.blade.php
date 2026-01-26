@extends('frontend.layouts.app')

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.config.autoParagraph = false;
        CKEDITOR.replace('description', {
            height: 600,
            // removeButtons: 'Strike,Underline,Subscript,Superscript,Blockquote,Outdent,Indent,NumberedList,RemoveFormat,Source,Image,Table,HorizontalRule,SpecialChar,Anchor,Unlink,Link,Scayt,Format,Styles,About,Cut,Copy,Paste,PasteText,PasteFromWord,Maximize,Undo,Redo',
            removeButtons: 'Subscript,Superscript,Blockquote,Outdent,Indent,NumberedList,RemoveFormat,Source,Image,Table,HorizontalRule,SpecialChar,Anchor,Unlink,Link,Scayt,Format,Styles,About,Cut,Copy,Paste,PasteText,PasteFromWord,Maximize,Undo,Redo',
            removePlugins: 'elementspath',
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <input type="hidden" data-property="{{$property->id}}" id="property-input"/>
        <div class="form-group row">
            <div class="d-flex flex-row">
                <div class="p-2">
                    <a href="{{route('properties.index', app()->getLocale())}}"
                       class="btn btn-danger">{{trans('global.back')}}</a>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <div class="alert alert-primary" role="alert">
                    {{trans('global.view')}} <a target="_blank"
                                                href="{{route('get.property.by.slug',[app()->getLocale(), $property->slug])}}"
                                                class="alert-link">https://abcmio.com/{{$property->slug}}</a>.
                </div>
            </div>
        </div>
{{--        <div class="row"  id="thumb_preview">--}}
{{--            <div class="container d-flex justify-content-center">--}}
{{--                <div class="col-md-6 ">--}}
{{--                    @if(isset($property) && $property->getMedia('photo')->isNotEmpty())--}}
{{--                        <img  src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}" id="img_preview"/>--}}
{{--                    @else--}}
{{--                        <label for="picture" class="col-6 col-form-label">{{trans('pages.forms.ads.global.feature_image.title')}}</label>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        @if(isset($property))
            <div class="row d-none">
                <div class="container d-flex justify-content-center">
                    <div class="col-md-6 ">
                        <div id="gallery"></div>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="dropZone">
                            <div class="dz-message align-middle">
                                <strong><i class="icon fas fa-camera"></i> Arrastre sus fotos aquí</strong>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--            <form class="dropzone" id="dropZone"--}}
            {{--                  action=""--}}
            {{--                method="POST"--}}
            {{--                enctype="multipart/form-data">--}}
            {{--                @csrf--}}
            {{--                @method('POST')--}}
            {{--            </form>--}}
        @endif

        <form class="form-horizontal" id="form_create"
              action="{{ route('properties.update',[ app()->getLocale(), $property]) }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('put')

            @include('frontend.properties._form')
            <div class="form-group row">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-success">{{trans('global.edit')}}</button>
                </div>
            </div>
        </form>


    </div>
@endsection

@section("styles")
    {{--  <link rel="stylesheet" href="{{asset("js/libs/dropzonejs/dropzone.css")}}">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet"/>

@endsection


@push('scripts')

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>


    <script src="{{asset('js/properties.js')}}"></script>
    <script src="{{asset('js/photos.js')}}"></script>

    <script>
        let limitFileUpload = 15;
        Dropzone.options.dropZone = {
            url: '{{route('property.gallery.upload', [app()->getLocale(), $property->id])}}',
            maxFilesize: 5, // MB
            acceptedFiles: '.jpeg,.jpg,.png',
            maxFiles: limitFileUpload,
            autoProcessQueue: true,
            parallelUploads: 4,
            addRemoveLinks: true,
            thumbnailWidth: 100,
            thumbnailHeight: 100,
            previewTemplate: '  <div class="dz-details">' +
                '<div class="thumb"><div class="dz-size" data-dz-size></div>  ' +
                '<img data-dz-thumbnail alt="Click me to remove the file."  /> <div data-dz-remove></div><div>' +
                '  </div>' +
                '  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
                // '  <div class="dz-success-mark"><span>✔</span></div>' +
                // '  <div class="dz-error-mark"><span>✘</span></div>' +
                // '  <div class="dz-error-message"><span data-dz-errormessage></span></div>' +
                '</div>',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 5,
                width: 40960,
                height: 40960,
            },
            uploadprogress: (file, progress, bytesSent) => {
                if (file.previewElement) {
                    const progressElement = file.previewElement.querySelector(".dz-upload");
                    progressElement.style.width = progress + "%";
                    $(".dz-upload").html(progress + "%");
                }
            },
            queuecomplete: (file, response) => {
                // console.log({file:file,response:response});
                $("#photo-dropzone").find(".dz-complete").remove();
            },
            success: (file, response) => {
                myDropzone = this;
                if(response.success) {

                    $("#photo-dropzone").find(".dz-complete").remove();
                    $(".dropzone.dz-started .dz-message").show();
                    const limitFilesUploaded = response.total;

                    if (limitFilesUploaded >= 15) {
                        $("#photo-dropzone").hide();
                    } else {
                        $("#photo-dropzone").show();
                    }

                    fetchProductGallery();

                } else {
                }

            },
            init: () => {
                fetchProductGallery();

            },
            error: (file, response) => {

                if ($.type(response) === 'string') {
                    let message = response; //dropzone sends it's own error messages in string
                    Swal.fire({
                        title: 'Error',
                        text: message,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    })
                } else {
                    const {file: errors} = response;
                    const messages = errors.map((error) => {
                        const {file: text} = error;
                        return text;
                    })
                    Swal.fire({
                        title: 'Error',
                        text: messages,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }

                file.previewElement.classList.add('dz-error');
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]');
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i];
                    _results.push(node.textContent = message);
                }

                return _results;
            }
            //  End Error function
        }

        $(document).on("click", "#gallery .remove", function (e) {
            e.preventDefault();
            const obj = $(this);
            const id = $(this).data("id");
            {{--            let url = '{{route('remove.product.photo',[app()->getLocale(),$property])}}';--}}
            const url = '{{route('property.gallery.delete', [app()->getLocale(), $property->id])}}';
            $("#photo-" + id).find(".info").html($("<span>", {text: 'Borrando...'}));

            Swal.fire({
                title: 'Desea eliminar esta foto?',
                text: 'Desea continuar',
                icon: 'error',
                showDenyButton: false,
                showCancelButton: true,
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                // denyButtonText: `No borrar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    window.axios
                        .post(url, {key: id})
                        .then((response) => {
                            const limitFilesUploaded = response.data.total;
                            if (limitFilesUploaded >= 15) {
                                $("#photo-dropzone").hide();
                            } else {
                                $("#photo-dropzone").show();
                            }
                            if (response.data.success) {
                                // console.log("Deleted:",response,obj.parent());
                                $("#photo-" + id).remove();
                                Swal.fire('Foto eliminada!', '', 'success')
                            } else {
                            }
                    }).catch( err => console.log );

                } else if (result.isDenied) {
                    // Swal.fire('Changes are not saved', '', 'info')
                }
            })

        });

        const formatBytes = (bytes, decimals = 2) => {
            if (bytes === 0) {
                return '0 Bytes';
            }
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        const fetchProductGallery = () => {
            $("#gallery").html('');
            window.axios
                .get('/api/property/images/' + $("#property-input").data('property'),
                    {
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                    }
                )
                .then((response) => {
                    const {data} = response;
                    const {result: images} = data;
                    images.map(({id, url, size, thumb}) => {
                        $("#gallery")
                            .append(
                                $("<div>", {class: 'preview', "id": 'photo-' + id})
                                    .append($("<div>", {class: "thumb"})
                                        .append($("<a>", {href: url}).attr("data-fancybox", "gallery")
                                            .append($("<img>", {src: thumb}))
                                        )
                                    )
                                    .append($("<div>", {class: 'info'})
                                        .append($("<span>", {text: formatBytes(size)}))
                                        .append($("<a>", {href: '#', class: 'remove'}).attr("data-id", id)
                                            .append($("<span>", {text: 'Borrar'})
                                                .append($("<i>", {class: 'fas fa-trash-alt'}))
                                            )
                                        )
                                    )
                                    .append($("<div>", {class: 'clearFix'}))
                            );
                    })
                })
                .finally( () => {
                    // .dz-image-preview
                    $("#dropZone").find(".dz-complete").remove();
                    $(".dropzone.dz-started .dz-message").show();
                })
                .catch(err => console.log(err));
        }

        const fetchGallery = (array) => {
            if (array.length) {
                const gallery = document.getElementById('gallery');
                gallery.innerHTML = "";
                array.map(media => {
                    let {url, key} = media;
                    let photo = document.createElement('photo');
                    let img = document.createElement('img');
                    let trash = document.createElement('i');

                    trash.className = "fas fa-trash trash red act-delete";
                    img.src = url;
                    img.className = 'thumb';
                    photo.className = 'photo';
                    photo.appendChild(trash);
                    photo.appendChild(img);
                    gallery.appendChild(photo);
                    trash.addEventListener('click', () => {
                        if (confirm('Desea eliminar esta foto?')) {
                            window.axios.delete('{{route('property.gallery.delete', [app()->getLocale(), $property->id])}}', {
                                data: {
                                    key: key
                                }
                            }).then(resp => {
                                if (resp.data.success) {
                                    loadGallery();
                                }
                            })
                        }
                    });
                });
            }
        }
        const loadGallery = () => {
            {{--window.axios.post('{{route('property.gallery', [app()->getLocale(), $property->id])}}')--}}
            {{--    .then( resp => {--}}
            {{--        if(resp.data.data) {--}}
            {{--            console.log(resp.data.data);--}}
            {{--            fetchGallery(resp.data.data);--}}
            {{--        }--}}
            {{--    }).catch(e => {--}}
            {{--    console.error(e);--}}
            {{--});--}}
        }
    </script>
@endpush
