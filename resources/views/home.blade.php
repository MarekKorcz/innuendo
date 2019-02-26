@extends('layouts.app')

@section('content')
<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header text-center">
                    <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}}</strong>
                </div>
                <div class="card-body">
                    <div class="row">
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
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            @if (auth()->user()->isEmployee)
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
                                <h3>Subscriptions card (option to add new one or link to show current one)</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection