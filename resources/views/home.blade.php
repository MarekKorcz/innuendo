@extends('layouts.app')

@section('content')
<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}} {{$user->surname}}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if (auth()->user()->isBoss)
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
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
                        @elseif (!auth()->user()->isEmployee && !auth()->user()->isAdmin)
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-center">Wizyty</h4>
                                        <p class="card-text text-center">
                                            Widok wszystkich wizyt
                                        </p>
                                        <div class="text-center">
                                            <a class="btn btn-success btn-lg" href="{{ URL::to('appointment/index/') }}">
                                                Pokaż
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            @if (auth()->user()->isEmployee || auth()->user()->isAdmin)
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-center">Grafiki</h4>
                                        <p class="card-text text-center">
                                            Widok wszystkich otwartych grafików
                                        </p>
                                        <div class="text-center">
                                            <a class="btn btn-success btn-lg" href="{{ URL::to('/employee/backend-graphic') }}">
                                                Pokaż
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if ($hasPurchasedSubscription)
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-center">Twoje pakiety</h4>
                                            <p class="card-text text-center">
                                                Widok wszystkich wykupionych pakietów
                                            </p>
                                            <div class="text-center">
                                                <a class="btn btn-success btn-lg" href="{{ URL::to('/user/subscription/purchased/property/list') }}">
                                                    Pokaż
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title text-center">Wykup pakiet</h4>
                                            <p class="card-text text-center">
                                                Widok pakietów masaży dostępnych w ofercie
                                            </p>
                                            <div class="text-center">
                                                <a class="btn btn-success btn-lg" href="{{ URL::to('/user/subscription/list') }}">
                                                    Pokaż
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        @if (auth()->user()->isAdmin)
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-center">Konta użytkowników</h4>
                                        <p class="card-text text-center">
                                            Widok z listą wszystkich użytkowników
                                        </p>
                                        <div class="text-center">
                                            <a class="btn btn-success btn-lg" href="{{ URL::to('/admin/user/list') }}">
                                                Pokaż
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection