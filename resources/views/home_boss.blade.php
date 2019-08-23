@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="card-header text-center">
        <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}} {{$user->surname}}</strong>
    </div>
    <div class="wrapper">
        @if ($user->isApproved == 1)
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Twoje wizyty</h4>
                    <p class="card-text text-center">
                        Widok Twoich wizyt 
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('/appointment/index') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>

    <!--        <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Grafiki</h4>
                    <p class="card-text text-center">
                        Grafiki wraz z opisem lokalizacji oraz wykonujących zabiegi
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('/user/properties') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>-->

    <!--        <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Twoje lokalizacje</h4>
                    <p class="card-text text-center">
                        Widok Twoich lokalizacji z listą pracowników do nich przynależących
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('boss/property/list/') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>-->

    <!--        <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Pakiety</h4>
                    <p class="card-text text-center">
                        Widok z pakietami przypisanymi do lokalizacji
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('boss/properties/subscription/purchase/') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>-->

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Panel zarządzania lokalizacjami i subskrypcjami</h4>
                    <p class="card-text text-center">
                        Widok z lokalizacjami oraz z przypisanymi do nich subskrypcjami wraz z pracownikami zarejestrowanymi do korzystania z nich 
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('boss/subscription/list/0/0') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Panel z zapytaniami o otwarcie grafików</h4>
                    <p class="card-text text-center">
                        Widok z listą zapytań o otwarcie w danych dniach grafików
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('boss/graphic-requests') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Generacja kodów rejestracji</h4>
                    <p class="card-text text-center">
                        Widok generacji kodów do rejestracji dla praconików w celu przydzielenia ich do odpowiednich pakietów
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('boss/codes/') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center">Umów się z nami</h4>
                    <p class="card-text text-center">
                        Skontaktuj się z nami w celu przeprowadzenia weryfikacji i ułatwienia dalszego etapu (todo: do poprawy na bank)
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success btn-lg" href="{{ URL::to('/boss/approve/messages') }}">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection