@extends('bio.app')
@section('content')

{!! Html::style('css/bio/service-section.css') !!}
{!! Html::script('js/bio/service-section.js') !!}

    <div class="service-section">
        <div id="header">
            <h1>Bioenergoterapia sesja</h1>
            <p style="font-size: 1.5rem;">
                60minut &nbsp;|&nbsp;  150zł
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
                    <hr>
                    <div id="localization">
                        <h3 style="text-align: center;">Lokalizacja i dane kontaktowe</h3>
                        <div class="row">
                            <div class="offset-2"></div>
                            <div class="col-sm-12 col-md-8">
                                <span>Tel: 518475207</span><br>
                                <span>E-mail: bioenergoterapia.waw@gmail.com</span><br>
                                <span>Adres: Stawki 2, Warszawa</span>
                            </div>
                            <div class="offset-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offset-1"></div>
        </div>
    </div>

@endsection