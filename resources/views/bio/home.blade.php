@extends('bio.app')
@section('content')

{!! Html::style('css/bio/home.css') !!}

    <div class="header-image-section">
        <div class="header-image-section-text container">
            <div class="border">
                <p>
                    BIOENERGOTERAPIA <br>
                    PATRYCJA DOLATA
                </p>
                <p style="font-size: 1.5rem;">Naturalne metody leczenia</p>
            </div>
        </div>
    </div>

    <div class="first-section-header">
        <p>MOŻLIWOŚCI LECZENIA</p>
        <p style="font-size: 1.6rem;">Wyjątkowe, indywidualne podejście</p>
    </div>


    <div class="row three-column-section">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="box">
                <div class="box-image">
                    <img class="first-image-in-box" src="/img/woman-lying.jpg">
                </div>
                <div class="box-text">
                    <p>
                        SESJA RÓWNOWAŻENIA <br> 
                        CZAKR
                    </p>
                    <p style="color: black;">Starożytna nauka</p>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="box">
                <div class="box-image">
                    <img class="first-image-in-box" src="/img/woman-praying.jpg">
                </div>
                <div class="box-text">
                    <p>TRENING RELAKSACYJNY</p>
                    <p style="color: black; padding-top: 2rem;">Naturalne uzdrawianie</p>
                </div>
            </div>        
        </div>
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="box">
                <div class="box-image">
                    <img class="first-image-in-box" src="/img/head-massage.jpg">
                </div>
                <div class="box-text">
                    <p>BIOENERGOTERAPIA</p>
                    <p style="color: black; padding-top: 2rem;">Zrównoważony poziom chi</p>
                </div>
            </div>
        </div>
    </div>

<div class="row quote-section">
    <div class="col-12">
        <p style="font-size: 1.5rem; color: #95a2a2;">„Każdy człowiek jest autorem swojego zdrowia lub choroby”.</p>
        <p>
            <cite>
                Budda
            </cite>
        </p>
    </div>
</div>

@endsection