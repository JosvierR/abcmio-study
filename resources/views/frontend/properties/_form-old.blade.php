<p id="loading" for="" class="alert alert-warning justify-content-center"><strong>Espere</strong> Cargando...</p>
<div class="form-group row">
    <div id="thumb_preview">
        {{--            <img src="{{\Storage::url($property->image_path)}}" alt="{{\Storage::url($property->image_path)}}" />--}}
        @if(isset($property))
            <img src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}" />
        @else
            <div class="img_box">
                <img src="" alt="" id="img_preview">
            </div>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="picture" class="col-sm-2 col-form-label">Imágen Principal </label>
    <div class="col-sm-10">
        <input disabled type="file" class="form-control" name="picture" id="picture" accept="image/*" >
    </div>
</div>
@if(isset($property))
    {{--@if( ($property->photos->count())<8)--}}
    <h3 id="max-photos-title">Puede agregar hasta 6 fotos.</h3>

    {{--<div class="input-group control-group img_div form-group col-md-4" >--}}
    {{--    <input disabled type="file" name="photos_url[]" class="form-control" accept="image/*">--}}
    {{--    <!-- Add More Button -->--}}
    {{--    <div class="input-group-btn">--}}
    {{--        <button class="btn btn-success btn-add-more" type="button"><i class="fa fa-plus"></i> Agregar</button>--}}
    {{--    </div>--}}
    {{--    <!-- End -->--}}

    {{--</div>--}}
    {{--@else--}}
    {{--    <h3>Debe eliminar fotos para agregar más</h3>--}}
    {{--@endif--}}
    @if(isset($property) && isset($property))
        <div class="form-group row">
            <div id="gallery">
                @foreach($property->getMedia("gallery") as $key => $photo)
                    <div class="photo">
                        <i class="fas fa-trash trash red act-delete" data-toggle="tooltip" data-token="{{ csrf_token() }}" data-id="{{$property->id}}" data-key="{{$photo->id}}" data-placement="top" title="Borrar Foto "></i>
                        {{--                        <img src="{{\Storage::url($photo->photo_url)}}" alt="{{\Storage::url($photo->photo_url)}}" class="thumb"/>--}}
                        <img src="{{$photo->getUrl("medium")}}" alt="{{$photo->getUrl("medium")}}" class="thumb"/>

                    </div>
                @endforeach
                <div class="clearFix"></div>
            </div>
        </div>
    @endif
    <div class="form-group row">
        <!-- Add More Image upload field  -->
        <div class="clone hide " data-max="{{ 7 - ($property->photos->count()) }}">
            <div class="control-group input-group form-group col-md-4 clones" style="margin-top:10px">
                <input disabled type="file" name="photos_url[]" class="form-control" accept="image/*">
                <div class="input-group-btn">
                    <button class="btn btn-danger btn-remove" type="button"><i class="fa fa-trash"></i> Remover</button>
                </div>
            </div>
        </div>
    </div>
@endif


<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label"><h3>Titulo <small class="required">*</small></h3></label>
    <div class="col-sm-10">
        <input disabled type="text" class="form-control required" name="title" id="title"
               placeholder="Campo requerido"
               {{--               value="{{isset($property)?$property->title:(empty(old("title"))?"":old('title'))}}"--}}
               value="{{old("title",$property->title ?? '')}}"
        >
    </div>
</div>
@if(isset($countries))
    {{--        <location-select :all_countries="{{$countries}}" @if(isset($property)):city="{{isset($property->city)?$property->city:-1}}" @endif ></location-select>--}}
    {{--        <div class="row form-group">--}}
    {{--            <div class="col-sm">--}}
    {{--                <select disabled name="country_id"  id="country" class="selectpicker form-control" data-live-search="true"  data-city="{{isset($cityId)?$cityId:-1}}">--}}
    {{--                    <option value="-1" >Todos los Paises</option>--}}
    {{--                    @foreach($countries as $country)--}}
    {{--                        <option  value="{{$country->id}}" @if(isset($countryId)&& $countryId==$country->id) selected @endif>--}}
    {{--                            {{$country->name}}--}}
    {{--                        </option>--}}
    {{--                    @endforeach--}}
    {{--                </select>--}}
    {{--            </div>--}}
    {{--            <div class="col-sm ">--}}
    {{--                <select disabled  name="city_id" id="city" class="form-control" data-live-search="true">--}}
    {{--                    <option value="-1" >Todas las Ciudades</option>--}}

    {{--                </select>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    <div class="form-group">
        <div class="col-xs-6">
            <label for="country_id"><h4>País <small class="required">*</small></h4>
                <?php $countrySelected = $property->city->country->id ?? $countryId ?? -1  ;?>
                <select disabled name="country"  id="country" class="selectpicker form-control" data-live-search="true" data-city="{{$property->city->id ?? $cityId ?? -1 }}">
                    {{--                        <option value="-1" >-Todos los Paises-</option>--}}
                    @foreach($countries as $country)
                        <option  value="{{$country->id}}"
                                 @if($countrySelected === $country->id) selected @endif
                        >
                            {{$country->name}}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label for="city"><h4>Ciudad <small class="required">*</small></h4></label>
            <select disabled name="city_id"  id="city" class="form-control" data-live-search="true" >
                {{--                    <option disabled value="-1">-Todas las Ciudades-</option>--}}

            </select>
        </div>
    </div>

@endif
@if(isset($categories))
    <div class="form-group">
        <div class="col-xs-6">
            <label for="category"><h4>Categoría <small class="required">*</small></h4>
                <select disabled name="category"  id="category" class="selectpicker form-control" data-live-search="true"  data-child="{{$property->category->id ?? -1 }}" data-property="{{$property->id ?? -1}}">
                    <option value="-1" :key="-1">-Seleccione una categoría-</option>
                    @foreach($categories as $key=>$category)
                        <option  value="{{$category->id}}" @if(isset($property->category->parent) && ($property->category->parent->id === $category->id)) selected @endif >
                            {{$category->name}}
                        </option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-6">
            <label for="sub_category"><h4>Sub Categorías <small class="required">*</small></h4></label>
            <select disabled name="category_id"  id="sub_category" class="form-control" data-live-search="true" >
                <option disabled value="-1">-Seleccione una  Sub Categoría-</option>

            </select>
        </div>
    </div>
    {{--        <category-form :all_categories="{{$categories}}"  @if(isset($property)):child="{{isset($property->category)?$property->category:-1}}" @endif ></category-form>--}}
@endif
{{--<div class="form-group row">--}}
{{--    <div class="col-sm-2">Visible</div>--}}
{{--    <div class="col-sm-10">--}}
{{--        <div class="form-check">--}}
{{--            <input disabled class="form-check-input" value="1" @if(isset($property)&&$property->is_public)checked="checked"@endif type="checkbox" id="is_public" name="is_public">--}}
{{--            <label class="form-check-label" for="is_public" >--}}
{{--                Público--}}
{{--            </label>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<div class="form-group row">
    <div class="col-sm-2">Estado</div>
    <div class="col-sm-10">
        <div class="form-check">
            <input disabled class="form-check-input" type="checkbox" value="1" @if(isset($property)&&$property->status=='enable')checked="checked"@endif id="status" name="status">
            <label class="form-check-label"  for="status">
                Activo
            </label>
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="website" class="col-sm-2 col-form-label">Website</label>
    <div class="col-sm-10">
        <input disabled type="text" class="form-control" id="website" name="website" placeholder="https://www.misitioweb.com" value="{{isset($property)?$property->website:old("website")}}">
    </div>
</div>

<div class="form-group row">
    <label for="phone" class="col-sm-2 col-form-label">Telefono</label>
    <div class="col-sm-10">
        <input disabled type="tel" class="form-control" id="phone" name="phone" placeholder="Digite su número de teléfono" value="{{isset($property)?$property->phone:old("phone")}}">
    </div>
</div>
<div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
        <input disabled type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico de Contacto" value="{{isset($property)?$property->email:old("email")}}">
    </div>
</div>
<div class="form-group row">
    <label for="address" class="col-sm-2 col-form-label">Dirección</label>
    <div class="col-sm-10">
        <input disabled type="text" class="form-control" id="address" name="address" placeholder="Digite su Dirección" value="{{isset($property)?$property->address:old("address")}}">
    </div>
</div>
<div class="form-group row">
    <label for="google_map" class="col-sm-2 col-form-label">Localizacion URL Google map </label>
    <div class="col-sm-10">
        <input disabled type="text" class="form-control" id="google_map" name="google_map" placeholder="https://goo.gl/maps/bMSYqDPCd1dSpfuP7" value="{{isset($property)?$property->google_map:old("google_map")}}">
    </div>
</div>
<fieldset class="form-group">
    {{--    <div class="form-group row">--}}
    {{--        <div class="col-sm-2">Teléfono</div>--}}
    {{--        <div class="col-sm-10">--}}
    {{--            <div class="form-check">--}}
    {{--                <input disabled class="form-check-input" type="checkbox" id="show_phone" name="show_phone">--}}
    {{--                <label class="form-check-label" for="show_phone">--}}
    {{--                    Mostrar en Auncio--}}
    {{--                </label>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="form-group row">--}}
    {{--        <div class="col-sm-2">Email</div>--}}
    {{--        <div class="col-sm-10">--}}
    {{--            <div class="form-check">--}}
    {{--                <input disabled class="form-check-input" type="checkbox" value="1" id="show_email" name="show_email" @if(isset($property)&&(int)$property->show_email)checked="checked"@endif>--}}
    {{--                <label class="form-check-label" for="show_email">--}}
    {{--                    Mostrar en Auncio--}}
    {{--                </label>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    <div class="form-group row">--}}
    {{--        <div class="col-sm-2">Website</div>--}}
    {{--        <div class="col-sm-10">--}}
    {{--            <div class="form-check">--}}
    {{--                <input disabled class="form-check-input" type="checkbox" id="show_website" name="show_website">--}}
    {{--                <label class="form-check-label" for="show_website">--}}
    {{--                    Mostrar en Auncio--}}
    {{--                </label>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="form-group row">
        <div class="col-sm-2">Mensajes</div>
        <div class="col-sm-10">
            <div class="form-check">
                <input disabled class="form-check-input" value="1" type="checkbox" id="send_message" name="send_message" @if(isset($property)&&$property->send_message)checked="checked"@endif>
                <label class="form-check-label" for="send_message">
                    Recibir Mensajes
                </label>
            </div>
        </div>
    </div>
</fieldset>
{{--<div class="form-group row">--}}
{{--    <label for="short_description">Breve Descripción</label>--}}
{{--    <textarea class="form-control" id="short_description" name="short_description" rows="3">{{isset($property)?$property->short_description:old('short_description')}}</textarea>--}}
{{--</div>--}}
<div class="form-group row">
    <label for="description">Descripción</label>
    <div id="textarea_holder">
        <textarea class="form-control summernote" id="description" name="description" rows="3">{{isset($property)?$property->description:old('description')}}</textarea>
    </div>
</div>


