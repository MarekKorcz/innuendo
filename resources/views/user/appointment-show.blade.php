@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                Wróć
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li>
                PLACE FOR DELETE APPOINTMENT BUTTON
                <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                    Wszystkie wizyty (change href)
                </a>
            </li>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Wizyta dnia: <strong>{{$day}} {{$month}} {{$year}}</strong></h2>
        </div>
        <p>Godzina wizyty: <strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
        <p>Całkowity czas wizyty: <strong>{{$appointment->minutes}} minut</strong></p>
        <p>Rodzaj wizyty: <strong>{{$item->name}}</strong></p>
    </div>    
</div>
@endsection