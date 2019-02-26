@extends('layouts.app')
@section('content')
<div class="container">

    <h1 class="text-center" style="padding: 2rem;">Grafik w:</h1>

    <div class="container">
        @for ($i = 1; $i <= count($calendars); $i++)
            @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                <div class="row padding">
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid" style="width: 200px; height: 280px;"/>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$calendars[$i]->property->name}}</h5>
                                <p class="card-text">
                                    {!!$calendars[$i]->property->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendars[$i]->id . '/0/0/0') }}">
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
                                <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid" style="width: 200px; height: 280px;"/>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$calendars[$i]->property->name}}</h5>
                                <p class="card-text">
                                    {!!$calendars[$i]->property->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendars[$i]->id . '/0/0/0') }}">
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
                            <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid" style="width: 200px; height: 280px;"/>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$calendars[$i]->property->name}}</h5>
                            <p class="card-text">
                                {!!$calendars[$i]->property->description!!}
                            </p>
                            <div class="text-center">
                                <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendars[$i]->id . '/0/0/0') }}">
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
        
        <br>
        
    </div>
</div>
@endsection