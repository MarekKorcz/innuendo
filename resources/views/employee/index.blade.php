@extends('layouts.app')

@section('content')

{!! Html::style('css/employee_index.css') !!}

<div class="container">
    <h1 class="text-center padding-top">Pracownicy</h1>
    @for ($i = 1; $i <= count($employees); $i++)
        @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
            <div class="row padding">
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        todo: zmienić zdjęcia!!!!
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$employees[$i]->description!!}
                            </p>
                            <div class="text-center">
                                <a href="{{ URL::to('/employee/' . $employees[$i]->slug) }}" class="btn btn-success">
                                    Zobacz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        @elseif ($i % 3 == 0)
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        todo: zmienić zdjęcia!!!!
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                            <p class="card-text">
                                {!!$employees[$i]->description!!}
                            </p>
                            <div class="text-center">
                                <a href="{{ URL::to('/employee/' . $employees[$i]->slug) }}" class="btn btn-success">
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
                    todo: zmienić zdjęcia!!!!
                    <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{$employees[$i]->name}}</h5>
                        <p class="card-text">
                            {!!$employees[$i]->description!!}
                        </p>
                        <div class="text-center">
                            <a href="{{ URL::to('/employee/' . $employees[$i]->slug) }}" class="btn btn-success">
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
</div>
@endsection