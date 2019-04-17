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
                <h4 class="card-title text-center">Twoje lokalizacje</h4>
                <p class="card-text text-center">
                    Widok Twoich lokalizacji z listą pracowników do nich przynależących
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('boss/property/list/') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Pakiety</h4>
                <p class="card-text text-center">
                    Widok z pakietami przypisanymi do lokalizacji
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('boss/properties/subscription/purchase/') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Panel zarządzania subskrypcjami</h4>
                <p class="card-text text-center">
                    Widok subskrypcji przypisanych do lokalizacji oraz pracowników zarejestrowanych do korzystania z nich 
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('boss/subscription/list/0/0') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Konfiguracja ustawień pracowników</h4>
                <p class="card-text text-center">
                    Widok z opcjami do rejestrowania pracowników, ich listą oraz informacjami dotyczącymi wykupionych subskrypcji
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('boss/dashboard/') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection