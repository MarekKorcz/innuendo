@extends('bio.app')
@section('content')

{!! Html::style('css/bio/reservation.css') !!}

    <div class="reservation-section">
        <div id="header">
            <h1>Nasze usługi</h1>
        </div>
        <div id="services">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="box">
                        <div class="box-image">
                            <img class="first-image-in-box" src="/img/bioenergoterapia-sesja.png">
                        </div>
                        <div class="box-text">
                            <p class="box-header">
                                Bioenergoterapia <br>
                                sesja
                            </p>
                            <hr>
                            <p style="color: black;">
                                60 minut <br>
                                150 zł
                            </p>
                            <div class="box-button">
                                <a class="btn" href="{{ route('bioSession') }}">Więcej info</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="box">
                        <div class="box-image">
                            <img class="first-image-in-box" src="/img/konchowanie.png">
                        </div>
                        <div class="box-text">
                            <p class="box-header">
                                Konchowanie uszu
                            </p>
                            <hr>
                            <p style="color: black;">
                                35 minut <br>
                                55 zł
                            </p>
                            <div class="box-button">
                                <a class="btn" href="{{ route('bioCandling') }}">Więcej info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection