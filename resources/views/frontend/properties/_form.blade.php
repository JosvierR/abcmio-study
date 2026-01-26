<h3><strong>{{trans('pages.forms.ads.global.title')}}</strong></h3>
<div class="row d-none"  id="thumb_preview">
    <div class="container d-flex justify-content-center">
        <div class="col-md-6 ">
            @if(isset($property) && $property->getMedia('photo')->isNotEmpty())
                <img  src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}" id="img_preview"/>
            @else
                <label for="picture" class="col-6 col-form-label">{{trans('pages.forms.ads.global.feature_image.title')}}</label>
            @endif
        </div>
    </div>
</div>
{{--    <div class="row"  id="thumb_preview">--}}
{{--        <div class="container d-flex justify-content-center">--}}
{{--            <div class="col-md-8 ">--}}
{{--                @if(isset($property) && $property->getMedia('photo')->isNotEmpty())--}}
{{--                    <img class="col-6" src="{{$property->ThumbImage}}" alt="{{$property->ThumbImage}}" id="img_preview"/>--}}
{{--                @else--}}
{{--                    <label for="picture" class="col-6 col-form-label">{{trans('pages.forms.ads.global.feature_image.title')}}</label>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--<div class="col-12">--}}
{{--    <input disabled type="file" class="form-control" name="picture" id="picture"--}}
{{--           accept="image/*">--}}
{{--</div>--}}
{{--@if(isset($property) && !$property->getMedia('gallery')->isEmpty())--}}
{{--<div class="form-group row">--}}
{{--    <div id="gallery">--}}
{{--        @foreach($property->getMedia('gallery') as $media)--}}
{{--            <img src="{{$media->getUrl('thumb') ?? ''}}" alt="" class="thumbs">--}}
{{--        @endforeach--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endif--}}
<div class="form-group">
    <div class="col-12 mt-4">
{{--        <label for="title" class="col-form-label">--}}
{{--            <h3>{{trans('pages.forms.ads.global.inputs.title.label')}} <small class="required">*</small></h3>--}}
{{--        </label>--}}
        <input disabled type="text" class="form-control required" name="title" id="title"
               placeholder="{{trans('pages.forms.ads.global.inputs.title.placeholder')}}"
               value="{{old("title",$property->title ?? '')}}"
        >
    </div>
</div>

<div class="form-group">
    <div class="col-12 mt-4">
{{--        <label for="business" class="col-form-label">--}}
{{--            <h3>{{trans('pages.forms.ads.global.inputs.business_name.label')}} </h3>--}}
{{--        </label>--}}
        <input disabled type="text" class="form-control " name="business_name" id="business"
               placeholder="{{trans('pages.forms.ads.global.inputs.business_name.placeholder')}}" value="{{old('business_name', $property->business_name ?? '')}}">
    </div>
</div>

<div class="form-group">
    <div class="col-12 mt-4">
        <label for="country_id">
            <h4 class="required">{{trans('pages.forms.ads.global.inputs.country.label')}} <small class="required">*</small></h4>
            <?php $countrySelected = $property->country->id ?? $countryId ?? -1  ;?>
            <select
                    id="country_id"
                    name="country_id"
                    class=" form-control"
                    data-live-search="true"

            >
{{--                <option value="">--}}
{{--                    {{trans('pages.forms.ads.global.inputs.country.placeholder')}}--}}
{{--                </option>--}}
                @foreach(LocationRepository::getCountries() ?? [] as $country)
                    <option
                            value="{{$country->id}}"
                            {{$countrySelected === $country->id ? 'selected' : ''}}
                    >
                        {{$country->name}}
                    </option>
                @endforeach
            </select>
        </label>
    </div>
</div>

<div class="form-group">
    <div class="col-12 mt-4">
{{--        <label for="city">--}}
{{--            <h4>{{trans('pages.forms.ads.global.inputs.state.label')}}<small class="required">*</small></h4>--}}
{{--        </label>--}}
        <input disabled name="city" id="city" class="form-control required" data-live-search="true"
               placeholder="{{trans('pages.forms.ads.global.inputs.state.placeholder')}}"
               value="{{old("city",$property->city ?? '')}}"
        />
    </div>
</div>

<div class="form-group">
    <div class="col-12 mt-4">
        <label for="category">
            <h4>{{trans('pages.forms.ads.global.inputs.category.label')}} <small class="required">*</small></h4>
        </label>
        <select disabled name="category"  id="category" class=" form-control" data-live-search="true"  data-child="{{$property->category->id ?? -1 }}" data-property="{{$property->id ?? -1}}">
{{--            <option value="-1" :key="-1">{{trans('pages.forms.ads.global.inputs.category.placeholder')}}</option>--}}
            @foreach(CategoryRepository::all() as $key=>$category)
                <option  value="{{$category->id}}" @if(isset($property->category->parent) && ($property->category->parent->id === $category->id)) selected @endif >
                    {{$category->name}}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <div class="col-12 mt-4">
        <label for="sub_category">
            <h4>{{trans('pages.forms.ads.global.inputs.sub-category.label')}} <small class="required">*</small></h4>
        </label>
        <select disabled name="category_id" id="sub_category" class="form-control"
                data-live-search="true">
            <option value="-1">{{trans('pages.forms.ads.global.inputs.sub-category.placeholder')}}</option>
        </select>
    </div>
</div>

<div class="form-group row">
{{--    <div class="col-3 mt-4">{{trans('pages.forms.ads.global.inputs.status.label')}}</div>--}}
{{--    <div class="col-3 mt-4">--}}
{{--        <div class="form-check">--}}
{{--            <input disabled--}}
{{--                   class="form-check-input"--}}
{{--                   type="checkbox"--}}
{{--                   value="1"--}}
{{--                   id="status"--}}
{{--                   @if(isset($property)&&$property->status=='enable')checked="checked"@endif--}}
{{--                   name="status">--}}
{{--            <label class="form-check-label" for="status">--}}
{{--                {{trans('pages.forms.ads.global.inputs.status.label')}} {{trans('pages.forms.ads.global.inputs.status.option')}}--}}
{{--            </label>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

<div class="form-group row">
{{--    <div class="col-3 mt-4">{{trans('pages.forms.ads.global.inputs.messages.label')}}</div>--}}
{{--    <div class="col-9 mt-4">--}}
{{--        <div class="form-check">--}}
{{--            <input disabled--}}
{{--                   class="form-check-input"--}}
{{--                   type="checkbox"--}}
{{--                   value="1"--}}
{{--                   id="send_message"--}}
{{--                   name="send_message"--}}
{{--                   @if(isset($property) && $property->send_message =='enable')checked="checked"@endif--}}
{{--            >--}}
{{--            <label class="form-check-label" for="send_message">--}}
{{--                {{trans('pages.forms.ads.global.inputs.messages.label')}} {{trans('pages.forms.ads.global.inputs.messages.option')}}--}}
{{--            </label>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>

<div class="form-group row mt-4">
    <label for="description">{{trans('pages.forms.ads.global.inputs.description.label')}}</label>
    <div id="textarea_holder" class="col-12 mt-4">
{{--    <textarea class="form-control "--}}
{{--              id="description"--}}
{{--              name="description"--}}
{{--              rows="3"--}}
{{--    >{{isset($property)?$property->description:old('description')}}</textarea>--}}
        <textarea class="form-control" required
                  class="form-control "
                  id="description"
                  name="description"
                  data-toggle="tooltip"
                  data-placement="left" style="font-size: 14px;"
                  title="Descripción"
                  placeholder="Descripción {{trans('global.required_field')}}"
                  rows="9">{!! old("description", $property->description ?? '') !!}</textarea>
    </div>
</div>

<h3 class="mt-5"><strong>{{trans('pages.forms.ads.global.inputs.contact_info.header.title')}}</strong></h3>

<div class="form-group row">
    <div class="col-12 mt-4">
        <label for="phone" class="col-2 col-form-label">{{trans('pages.forms.ads.global.inputs.contact_info.phone.label')}}</label>
        <input disabled type="tel" class="form-control" id="phone" name="phone"
               placeholder="{{trans('pages.forms.ads.global.inputs.contact_info.phone.placeholder')}}"
               value="{{isset($property)?$property->phone:old("phone")}}"
        >
    </div>
</div>
<div class="form-group row">
    <div class="col-12 mt-4">
        <label for="whatsapp_number" class="col-2 col-form-label">{{trans('pages.forms.ads.global.inputs.contact_info.whatsapp_number.label')}}
            <img src="{{asset('/images/logo-whtsapp.png')}}" width="30" class="d-flex" alt="">
        </label>
        <input disabled type="tel" class="form-control" id="whatsapp_number" name="whatsapp_number"
               placeholder="{{trans('pages.forms.ads.global.inputs.contact_info.whatsapp_number.placeholder')}}"
               value="{{old('whatsapp_number', $property->whatsapp_number ?? request()->get('whatsapp_number'))}}"
        >
    </div>
</div>

<div class="form-group row">
    <div class="col-12 mt-4">
        <label for="email" class="col-2 col-form-label">{{trans('pages.forms.ads.global.inputs.contact_info.email.label')}}</label>
        <input disabled type="email" class="form-control" id="email" name="email"
               placeholder="{{trans('pages.forms.ads.global.inputs.contact_info.email.placeholder')}}"
               value="{{isset($property)?$property->email:old("email")}}"
        >
    </div>
</div>

{{--<div class="form-group row">--}}
{{--    <div class="col-12 mt-4">--}}
{{--        <label for="address" class="col-2 col-form-label">{{trans('pages.forms.ads.global.inputs.contact_info.address.label')}}</label>--}}
{{--        <input disabled--}}
{{--               id="address"--}}
{{--               type="text"--}}
{{--               class="form-control"--}}
{{--               name="address"--}}
{{--               placeholder="{{trans('pages.forms.ads.global.inputs.contact_info.address.placeholder')}}"--}}
{{--               value="{{isset($property)?$property->address:old("address")}}"--}}
{{--        >--}}
{{--    </div>--}}
{{--</div>--}}

<div class="form-group row">
    <div class="col-12 mt-4">
        <label for="google_map" class="col-12 col-form-label">{{trans('pages.forms.ads.global.inputs.contact_info.google_map.title')}}
        </label>
        <input disabled type="url"
               class="form-control"
               id="google_map"
               name="google_map"
               placeholder="{{trans('pages.forms.ads.global.inputs.contact_info.google_map.placeholder')}}"
               value="{{isset($property)?$property->google_map:old("google_map")}}"
        >
    </div>
</div>

{{--<h3 class="mt-5"><strong>{{trans('pages.forms.ads.global.inputs.socials_media.social_network.label')}}</strong></h3>--}}

<div class="form-group row">
    <div class="col-12 mt-4">
        <label for="website" class="col-2 col-form-label">Website</label>
        <input disabled type="url" id="website" name="website"
               placeholder="https://www.misitioweb.com"
               class="form-control"
               value="{{isset($property)?$property->website:old("website")}}"
        >
    </div>
</div>

<div class="form-group mt-4">
    <label for="website" class="col-12 col-form-label">Red Social</label>
    <div class="col-12" style="display:flex;">
        <div class="col-10">
            <input type="text" id="website" name="social_network"
                   placeholder="https://www.facebook.com/ejemplo"
                   value="{{isset($property)?$property->social_network:old("social_network")}}"
                   class="form-control col-11">
        </div>
{{--        <div class="col-2 ml-4">--}}
{{--            <a href="#" type="addred" class="btn action action-btn btn-success">--}}
{{--                <i class="fas fa-plus" data-toggle="tooltip" data-placement="top"--}}
{{--                   title="Añadir Red"></i>--}}
{{--            </a>--}}
{{--        </div>--}}
    </div>
</div>
