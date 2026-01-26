@foreach($property->getMedia("gallery") ?? [] as $key => $photo)
    <div class="photo">
        <i class="fas fa-trash trash red act-delete"
           {{--                   data-toggle="tooltip" --}}
           data-token="{{ csrf_token() }}"
           data-id="{{$property->id}}"
           data-key="{{$photo->id}}"
           data-placement="top"
           title="Borrar Foto "></i>
        <img src="{{$photo->getUrl("medium")}}" alt="{{$photo->getUrl("medium")}}" class="thumb"/>
    </div>
@endforeach