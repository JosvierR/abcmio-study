@include('partials._loading')
{{--<div class="row justify-content-start">--}}
{{--    <section class="col-12 ">--}}
{{--        @php $URL = $searchUrl ?? "search.results" @endphp--}}
{{--        <form action="{{route($URL)}}" method="GET" id="search-form">--}}
{{--            @csrf--}}
{{--            @method('POST')--}}
{{--            @include('partials.search._form')--}}
{{--        </form>--}}
{{--    </section>--}}
{{--</div>--}}

<div class="row justify-content-start">
    <section class="col-12 ">
        <form action="{{route('search.results', app()->getLocale())}}" method="GET" id="search-form">

                <div class="container">

                    <div class="row">
                        <div class="col col-12 mb-2">
                            <select disabled
                                name="country_id"
                                id="country"
                                class=" form-control"
                                data-live-search="true"

                        >
                            <option  value="-2">@lang('search.form.select.all_countries')</option>
                            @foreach($countries ?? [] as $country)
                                <option value="{{$country->id}}"
                                        {{ (isset($countryId) && ($countryId == $country->id)) ? 'selected' : '' }}
                                >
                                    {{$country->NameLabel}}
                                </option>
                            @endforeach
                        </select>
                        </div>
                    </div>

                    <div class="row" id="category_id">
                        <div class="col col-12 mb-2">
                            <select
                                name="category_id"
                                id="category"
                                {{--                            class=" form-control selectpicker"--}}
                                class=" form-control "
                                data-live-search="true"
                                data-child="{{$SubCategoryId ?? '-1'}}"
                        >
                            <option value="-1">@lang('search.form.select.all_categories')</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{$category->id}}"
                                        {{ (isset($categoryId) && ($categoryId == $category->id)) ? 'selected' : '' }}
                                >
                                    {{$category->NameLabel}}
                                </option>
                            @endforeach
                            <option value=""></option>
                        </select>
                        </div>
                    </div>
                    <div class="row" id="sub_category_id">
                        <div class="col col-12 mb-2">
                            <select name="sub_category_id" id="sub_category" class="form-control" data-live-search="true" data-option="@lang('search.form.select.all_subcategories')">
                                <option value="-2">
                                    @lang('search.form.select.all_subcategories')
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-12  mb-2">
                            <input
                                    type="text"
                                    class="form-control "
                                    name="city"
                                    placeholder="{{trans('search.form.input.city')}}"
                                    value="{{old('city', $post['city'] ?? '')}}"
                            />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-10 col-8">
                            <input
                                    type="text"
                                    class="form-control selectpicker"
                                    name="query"
                                    placeholder="@lang('search.form.input.search')"
                                    value="{{old('query', $post['query'] ?? '')}}"
                            />
                        </div>
                        <div class="col col-md-2 col-4">
                            <button class="btn btn-primary d-flex w-100 justify-content-center" type="submit" >
                                <i class="fa fa-search pr-2" style="line-height: inherit;height: auto;}"></i> {{trans('global.search')}}
                            </button>
                        </div>
                    </div><!--.row-->
                </div><!--.container-->

        </form>
    </section>
</div>
