@extends('layouts.app')

@section('content')

{!! Html::script('js/welcome.js') !!}
{!! Html::style('css/welcome.css') !!}
    
    <!--Carousel Wrapper-->
    <div id="carousel-example-2" class="carousel slide carousel-fade" data-ride="carousel">
        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-2" data-slide-to="1"></li>
            <li data-target="#carousel-example-2" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->
        <!--Slides-->
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
                <div class="view">
                    <img class="d-block w-100" src="/img/background1.jpg">
                    <div class="mask rgba-black-light"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Light mask</h3>
                    <p>First text</p>
                </div>-->
            </div>
            <div class="carousel-item">
                <!--Mask color-->
                <div class="view">
                    <img class="d-block w-100" src="/img/background2.jpg">
                    <div class="mask rgba-black-strong"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Strong mask</h3>
                    <p>Secondary text</p>
                </div>-->
            </div>
            <div class="carousel-item">
                <!--Mask color-->
                <div class="view">
                    <img class="d-block w-100" src="/img/background3.jpg">
                    <div class="mask rgba-black-slight"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Slight mask</h3>
                    <p>Third text</p>
                </div>-->
            </div>
        </div>
        <!--/.Slides-->
        <!--Controls-->
        <a class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!--/.Controls-->
    </div>
    <!--/.Carousel Wrapper-->
    
    <!--Jumbotron-->
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-10">
                <h2>
                    Bolą Cie plecy? Powierz je w nasze ręce!
                </h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-2">
                <a href="#">
                    <button type="button" class="btn btn-outline-secondary btn-lg">
                        Lorem ipsum
                    </button>
               </a>
            </div>
        </div>
    </div>
    
    <!--Welcome section-->
    <div class="container-fluid padding">
        <div class="row welcome text-center">
            <div class="col-12">
                <h1 class="display-4">Built with ease</h1>
            </div>
            <hr>
            <div class="col-12">
                <p class="lead">Lorem ipsum dolor sit amet, consectetur 
                    adipiscing elit. Maecenas erat purus, rutrum ut lacus a, 
                    fringilla volutpat tortor. Duis bibendum tincidunt posuere. 
                    Donec cursus nibh nunc, non tincidunt sem dictum vitae. 
                    Lorem ipsum dolor sit amet, consectetur dipiscing elit.
                </p>
            </div>
        </div>
    </div>
    
    <!--Two Column Section-->
    <div class="padding welcome">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Lorem Ipsum</h4>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur 
                        adipiscing elit. Maecenas erat purus, rutrum ut lacus a, 
                        fringilla volutpat tortor. Duis bibendum tincidunt posuere. 
                        Donec cursus nibh nunc, non tincidunt sem dictum vitae.
                    </p>
                </div>
                <div class="col-sm-6 text-center">
                    <img src="img/column.jpg" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    
    <!--Fixed background-->
    <div id="fixed">
        <div class="landing-text">
            <h1>BOOTSTRAP</h1>
            <h3>Learn the basic building blocks.</h3>
            <a href="#" class="btn btn-outline-secondary btn-lg">
                Get Started
            </a>
        </div>
    </div>
    
    <!--Three Column Section-->
    <div class="container-fluid padding">
        <h1 class="text-center">Plany abonamentowe</h1>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/smile-beam')
                <h3>2 zabiegi - 110 zł miesięcznie</h3>
                <p>Cena jednego masażu - 55 zł</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/laugh-beam')
                <h3>2 zabiegi - 100 zł miesięcznie</h3>
                <p>Cena jednego masażu - 50 zł</p>
            </div>
        </div>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/grin-hearts')
                <h3>4 zabiegi - 180 zł miesięcznie</h3>
                <p>Cena jednego masażu - 45 zł</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/surprise')
                <h3>4 zabiegi - 160 zł miesięcznie</h3>
                <p>Cena jednego masażu - 40 zł</p>
            </div>
        </div>
    </div>
        
    <!--Two Column Section-->
    <div class="padding welcome">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img src="img/column2.jpg" class="img-fluid">
                </div>
                <div class="col-sm-6 text-center">
                    <h4>Lorem Ipsum</h4>
                    <p class="lead">Lorem ipsum dolor sit amet, consectetur 
                        adipiscing elit. Maecenas erat purus, rutrum ut lacus a, 
                        fringilla volutpat tortor. Duis bibendum tincidunt posuere. 
                        Donec cursus nibh nunc, non tincidunt sem dictum vitae.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection