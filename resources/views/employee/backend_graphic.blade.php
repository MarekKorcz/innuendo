@extends('layouts.app')
@section('content')

{!! Html::style('css/backend_graphic.css') !!}

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">Grafiki w:</h1>

    <div class="wrapper">
        @foreach ($calendars as $calendar)
            <div class="card">
                <div class="text-center">
                    <img src="{{ asset('img/rick-and-morty.jpg') }}" class="img-fluid" style="width: 200px; height: 280px;"/>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">{{$calendar->property->name}}</h5>
                    <p class="card-text">
                        {!!$calendar->property->description!!}
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendar->id . '/0/0/0') }}">
                            Zobacz
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection