<fieldset class="row form-group m-1">
    <div class="input-group">
        <input
                type="text"
               class="form-control"
               name="query"
               placeholder="@lang('search.form.input.search')"
               value="{{old('query',$post['query']??'')}}"
        >

        <div class="input-group-append">
            <button class="btn btn-primary"  type="submit">
                <i class="fa fa-search"></i> {{trans('pages.my_ads.form.buttons.search')}}
            </button>
        </div>
    </div>
    <div class="input-group">
        <div class="form-check mb-2 mr-sm-2">
            <input class="form-check-input" type="checkbox" id="is_publish" name="is_publish" @if(isset($post['is_publish'])&&$post['is_publish'])checked @endif value="1">
            <label class="form-check-label" for="is_publish">
                {{trans('pages.my_ads.form.checkbox.public_only')}}
            </label>
        </div>
    </div>

    {{--                <div class="row form-group">--}}
    {{--                    <div class="col-sm-9">--}}
    {{--                        <input disabled class="form-control" name="query" type="text" placeholder="Búsqueda" value="{{old('query',$post['query']??'')}}" />--}}
    {{--                    </div>--}}
    {{--                    <div class="col-sm-3">--}}
    {{--                        <button  type="submit" name="btn_search" class="btn btn-block btn-primary">Buscar</button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="form-check mb-2 mr-sm-2">--}}
    {{--                    <input class="form-check-input" type="checkbox" id="exact_match" name="exact_match" @if(isset($post['exact_match'])&&$post['exact_match'])checked @endif>--}}
    {{--                    <label class="form-check-label" for="exact_match">--}}
    {{--                        Búsqueda Exacta--}}
    {{--                    </label>--}}
    {{--                </div>--}}
</fieldset>
{{--                        <advanced-search  :all_countries="{{$countries}}" :parents_categories="{{$categories}}"  ></advanced-search>--}}