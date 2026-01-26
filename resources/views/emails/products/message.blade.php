@component('mail::message')
    Hola!

    Ha recibido un mensaje de: {{$post['name'] ?? ''}}, {{$post['email'] ?? ''}} <br/>

    @if(isset($post['phone']))
        Teléfono: {{$post['phone']}} <br/>
    @endif
    @component('mail::button', ['url' => route("home")])
        Volver a Skyisfy
    @endcomponent
    <p>
        {!! $post['message'] ?? '' !!}}
    </p>
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
