@extends('bio.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/bio/service-section.css') }}">
<script src="{{ asset('js/bio/service-section.js') }}"></script>

    <div class="service-section">
        <div id="header">
            <h1>Bioenergoterapia sesja</h1>
            <p style="font-size: 1.5rem;">
                60 minut &nbsp;|&nbsp; 150 zł
            </p>
            <div class="button">
                <a class="btn">Więcej info</a>
            </div>
        </div>
        <div class="row">
            <div class="offset-1"></div>
            <div class="col-sm-12 col-md-10">
                <img class="center" src="/img/bioenergoterapia-sesja.png">
            </div>
            <div class="offset-1"></div>
        </div>

        <div id="service-description" class="row">
            <div class="offset-1"></div>
            <div class="col-sm-12 col-md-10">
                <div id="description">
                    <p>Podczas pierwszej wizyty proszę o przeznaczenie dodatkowych 15 minut na wywiad.</p>
                    @include('bio.localization')
                </div>
            </div>
            <div class="offset-1"></div>
        </div>
    </div>

@endsection
