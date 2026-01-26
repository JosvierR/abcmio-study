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
        <form
              action="{{ route('properties.store', app()->getLocale()) }}"
              class="form-horizontal"
              id="form_create"
              method="POST"
              enctype="multipart/form-data"
        >
            @csrf
            @method('post')
            @include('frontend.properties._form')

            <div class="form-group row">
                <div class="col-12 mt-5 btns">
                    <button type="submit" class="btn btn-success">{{trans('pages.forms.ads.create.title')}}</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/properties.js')}}"></script>
@endpush
