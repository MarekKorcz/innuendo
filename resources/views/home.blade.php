@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="card-header text-center">
        <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}} {{$user->surname}}</strong>
    </div>
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Twoje wizyty</h4>
                <p class="card-text text-center">
                    Widok Twoich wizyt 
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('/appointment/index') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Grafiki</h4>
                <p class="card-text text-center">
                    Grafiki wraz z opisem lokalizacji oraz wykonujących zabiegi
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('/user/properties') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Pakiety</h4>
                <p class="card-text text-center">
                    Widok z Twoimi wykupionymi pakietami przypisanymi do lokalizacji
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('user/subscription/purchased/property/list/') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Wykup dodatkowy pakiet</h4>
                <p class="card-text text-center">
                    Widok z dostępnymi pakietami w wybranych lokalizacjach
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('user/properties/subscription/') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection