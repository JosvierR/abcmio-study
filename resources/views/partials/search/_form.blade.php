<div class="row form-group">
    <div class="col-sm">
        <select disabled name="country_id" id="country" class="selectpicker form-control" data-live-search="true"
                data-city="{{$cityId ?? '-1'}}">
            <option value="">Todos los Paises</option>
            @if(isset($countries))
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if(isset($countryId)&& $countryId==$country->id) selected @endif>
                        {{$country->NameLabel}}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-sm ">
        <select name="city_id" id="city" class="form-control" data-live-search="true">
            <option value="">Todas las Ciudades</option>
            {{--                                    <option  v-for="option in cities" v-bind:value="option.value" :key="option.id">--}}
            {{--                                        {{option.name}}--}}
            {{--                                    </option>--}}
        </select>
    </div>
</div>
<div class="row form-group">
    <div class="col-sm">
        <select name="category_id" id="category" class="selectpicker form-control" data-live-search="true"
                data-child="{{$SubCategoryId ?? '-1'}}">
            <option value="-1">Todas las Categoría</option>
            @foreach($categories as $category)
                <option value="{{$category->id}}" @if(isset($categoryId)&& $categoryId==$category->id) selected @endif>
                    {{$category->NameLabel}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-sm">
        <select name="sub_category_id" id="sub_category" class="form-control" data-live-search="true">
            <option value="-1">-Todas las Sub Categorías-</option>

        </select>
    </div>
</div>
{{--            @else--}}

{{--            @endauth--}}


<fieldset class="row form-group m-1">
    <div class="input-group">
        <input type="text" class="form-control" name="query" placeholder="Búsqueda"
               value="{{old('query',$post['query']??'')}}">
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i> Buscar
            </button>
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