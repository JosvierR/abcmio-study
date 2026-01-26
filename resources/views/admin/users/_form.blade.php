<div class="form-group row">
    @if(isset($parent))
        <h3>Creando Sub-Categoría de <strong>{{$parent->name}}</strong></h3>
    @else
        @if(isset($category)&&isset($category->parent))
            <h3>Actualizando Sub Categoría</h3>
        @else
            <h3>Actualizando Categoría</h3>
        @endif
    @endif

</div>
@if(isset($category)&&isset($category->parent))
    <div class="form-group row">
        <h3>{{$category->parent->name}}</h3>
    </div>
    @endif
<div class="form-group row">
    @if(isset($category)&&isset($category->parent))
        <a href="{{route('admin.category.child',$category->parent)}}" class="btn btn-primary">Volver</a>
    @else
        @if(isset($category)&&!isset($category->parent))
            <a href="{{route('admin.categories.index')}}" class="btn btn-primary">Volver</a>
        @else
         @endif
    @endif
</div>
<div class="form-group row">
    <label for="title" class="col-sm-2 col-form-label">Nombre <small class="required">*</small></label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name"
               value="{{isset($category)?$category->name:(empty(old("name"))?"":old('name'))}}"
        >
        @if(isset($parent))
            <input type="hidden" name="parent_id" value="{{$parent->id}}"/>
        @else
            <input type="hidden" name="parent_id" value="{{isset($category)?(int)$category->parent_id:0}}"/>
        @endif
        <input type="hidden" name="is_free" value="{{isset($category)?(int)$category->is_free:0}}"/>
    </div>
</div>





