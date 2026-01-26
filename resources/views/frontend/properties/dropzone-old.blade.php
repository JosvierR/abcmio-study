<div class="container">
    <form action="{{route("photos.update",[ app()->getLocale(), $property->id])}}"
          class="dropzone"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="fallback">
            <input name="file" type="file" multiple accept="image/jpeg"/>
        </div>
    </form>
</div>
