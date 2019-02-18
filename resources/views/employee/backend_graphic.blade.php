@extends('layouts.app')
@section('content')
<div class="container">

    <h1>Grafik w:</h1>
    
    <hr><br>

    <div class="container">
        @foreach ($calendars as $calendar)
            <a href="{{ URL::to('employee/backend-calendar/' . $calendar->id . '/0/0/0') }}">
                <div class="d-inline-block bg-warning" style="width: 250px; height: 350px; margin-left: 30px;">
                    <h3 class="text-center">{{$calendar->property->name}}</h3>
                    <div class="text-center">
                        <img src="{{ asset('img/rick-and-morty.jpg') }}" style="width: 200px; height: 280px;"/>
                    </div>
                </div>
            </a>
        </a>
        @endforeach
    </div>
</div>
@endsection