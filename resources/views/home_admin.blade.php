@extends('layouts.app')
@section('content')

{!! Html::style('css/properties_subscription_purchase.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="card-header text-center">
        <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}} {{$user->surname}}</strong>
    </div>
    <div class="wrapper">
        <div>
            <h1>Admin</h1>
        </div>
    </div>
</div>
@endsection