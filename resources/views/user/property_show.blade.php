@extends('layouts.app')

@section('content')

{!! Html::script('js/property_show.js') !!}

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{ $property->name }}</h1>
    
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
                    <img class="d-block w-100 img-fluid" src="/img/background1.jpg">
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
                    <img class="d-block w-100 img-fluid" src="/img/background2.jpg">
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
                    <img class="d-block w-100 img-fluid" src="/img/background3.jpg">
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
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>Opis</h3>
                <p>Nazwa: <strong>{{$property->name}}</strong></p>
                <p>Data powstania: <strong>{{ $propertyCreatedAt }}</strong></p>
                <span>Opis: {!! $property->description !!}</span>
                <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <img class="img-fluid" src="/img/column2.jpg">
            </div>
        </div>
    </div>
    
    <h2 class="text-center" style="padding: 2rem;">Pracownicy:</h2>
    
    @for ($i = 1; $i <= count($employees); $i++)
        @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
            <div class="row padding">
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        <img class="img-fluid" src="/img/rick-and-morty.jpg">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$employees[$i]->description!!}
                            </p>
                            <div class="text-center">
                                <a href="{{ URL::to('employee/' . $employees[$i]->slug) }}" class="btn btn-success btn-lg">
                                    Zobacz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        @elseif ($i % 3 == 0)
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        <img class="img-fluid" src="/img/rick-and-morty.jpg">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$employees[$i]->description!!}
                            </p>
                            <div class="text-center">
                                <a href="{{ URL::to('employee/' . $employees[$i]->slug) }}" class="btn btn-success btn-lg">
                                    Zobacz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                <div class="card">
                    <img class="img-fluid" src="/img/rick-and-morty.jpg">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                        <p class="card-text">
                            {!!$employees[$i]->description!!}
                        </p>
                        <div class="text-center">
                            <a href="{{ URL::to('employee/' . $employees[$i]->slug) }}" class="btn btn-success btn-lg">
                                Zobacz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endfor   
    
    @if (count($employees) % 3 != 0)
        </div>
    @endif
    
    <br>
</div>
@endsection