@extends('bio.app')
@section('content')

{!! Html::style('css/bio/service-section.css') !!}
{!! Html::script('js/bio/service-section.js') !!}

    <div class="service-section">
        <div id="header">
            <h1>Konchowanie uszu</h1>
            <p style="font-size: 1.5rem;">
                35 minut &nbsp;|&nbsp; 55 zł
            </p>
            <div class="button">
                <a class="btn">Więcej info</a>
            </div>
        </div>
        <div class="row">
            <div class="offset-1"></div>
            <div class="col-sm-12 col-md-10">
                <img class="center" src="/img/konchowanie.png">
            </div>
            <div class="offset-1"></div>
        </div>
        
        <div id="service-description" class="row">
            <div class="offset-1"></div>
            <div class="col-sm-12 col-md-10">
                <div id="description">
                    <p>Konchowanie, czyli inaczej - świecowanie uszu. Przywraca nadzieję 
                      osobom cierpiącym z powodu nawracających infekcji zatok, uszu oraz skarżących 
                      się na szumy w uszach. Katar sienny czy częste bóle głowy nie będą się w stanie 
                      oprzeć zbawiennemu działaniu tego zabiegu.</p>
                    @include('bio.localization')
                </div>
            </div>
            <div class="offset-1"></div>
        </div>
    </div>

@endsection