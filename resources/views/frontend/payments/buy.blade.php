@extends('frontend.layouts.app')

@section('content')
    <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form"  action=" {{route('paypal.pay', app()->getLocale())}}">
        @csrf
        @method('post')
{{--        <h3 class="w3-text-blue">Método de pago: {{$credit->name}}</h3>--}}
        <h4>ABCMIO - Cuenta de paypal</h4>
        <p class="alert alert-info d-inline-block m-2 p-2"><strong class="font-weight-bold">Importante:</strong><small> Para pagar con paypal debe hacer click en strgon'Pagar con Paypal'</small></p>
        <p>
            <label class="w3-text-blue"><b>Total a pagar US$ {{$credit->TotalPrice}}</b></label>
            <input class="w3-input w3-border" name="amount" type="hidden" value="{{$credit->price}}"></p>
            <input class="w3-input w3-border" name="credit" type="hidden" value="{{$credit->id}}">
        </p>

        <button class="w3-btn w3-blue btn "><i class="fab fa-paypal h1"></i> Pagar con PayPal</button></p>
    </form>

@endsection
