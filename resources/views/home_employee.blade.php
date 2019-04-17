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
                <h4 class="card-title text-center">Grafiki</h4>
                <p class="card-text text-center">
                    Lista grafików w lokalizacjach
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('/employee/backend-graphic') }}">
                        Pokaż
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection