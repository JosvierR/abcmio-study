@component('mail::message')
# Reseteo de Contraseña

Su nueva contraseña: <strong>{{$password}}</strong>

@component('mail::button', ['url' => $url])
Volver a ABCMIO
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
