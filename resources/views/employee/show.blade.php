@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center padding">{{ $employee->name }} {{$employee->surname}}</h1>
    
    <hr>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3 style="padding: 9px;">Opis</h3>
                <p>Imię: <strong>{{$employee->name}} {{$employee->surname}}</strong></p>
                <p>Adres e-mail: <strong>{{$employee->email}}</strong></p>
                <p>Pracuje od: <strong>{{ $employeeCreatedAt }}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    
                    <!--todo: zmień te zdjęcie-->
                    
                    <img class="img-fluid" src="/img/wladimir.jpg" width="300" height="450">
                </div>
            </div>
        </div>
    </div>

    @if ($calendars && count($calendars) == count($properties))
    
        <h2 class="text-center padding-top">Grafik w:</h2>
    
        @for ($i = 1; $i <= count($calendars); $i++)
            @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                <div class="row padding">
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                @svg('solid/home')
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                                <p class="card-text">
                                    {!!$properties[$i - 1]->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                        Zobacz
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            @elseif ($i % 3 == 0)
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                @svg('solid/home')
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                                <p class="card-text">
                                    {!!$properties[$i - 1]->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
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
                        <div class="text-center">
                            @svg('solid/home')
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$properties[$i - 1]->name}}</h5>
                            <p class="card-text">
                                {!!$properties[$i - 1]->description!!}
                            </p>
                            <div class="text-center">
                                <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                                    Zobacz
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endfor   

        @if (count($calendars) % 3 != 0)
            </div>
        @endif
    @endif
</div>
@endsection