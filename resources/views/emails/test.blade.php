@component('mail::message')
# Introduction

Este correo es de prueba

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
