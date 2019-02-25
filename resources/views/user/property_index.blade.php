@extends('layouts.app')

@section('content')

{!! Html::style('css/property_index.css') !!}

<div class="container">
    <h1 class="text-center padding-top">Lokalizacje</h1>
    @for ($i = 1; $i <= count($properties); $i++)
        @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
            <div class="row padding">
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$properties[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$properties[$i]->description!!}
                            </p>
                            <a href="{{ URL::to('user/property/' . $properties[$i]->slug) }}" class="btn btn-success">
                                Zobacz
                            </a>
                        </div>
                    </div>
                </div>
        @elseif ($i % 3 == 0)
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$properties[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$properties[$i]->description!!}
                            </p>
                            <a href="{{ URL::to('user/property/' . $properties[$i]->slug) }}" class="btn btn-success">
                                Zobacz
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                <div class="card">
                    <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{$properties[$i]->name}}</h5>
                        <p class="card-text">
                            {!!$properties[$i]->description!!}
                        </p>
                        <a href="{{ URL::to('user/property/' . $properties[$i]->slug) }}" class="btn btn-success">
                            Zobacz
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @endfor   
    
    @if (count($properties) % 3 != 0)
        </div>
    @endif
</div>
@endsection