

<div class="form-group row">

</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Nombre <small class="required">*</small></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name"
               value="{{isset($country)?$country->name:(empty(old("name"))?"":old('name'))}}"
        >
    </div>
</div>





