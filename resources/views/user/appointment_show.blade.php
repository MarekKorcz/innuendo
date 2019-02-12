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
                <a class="btn btn-primary" href="{{ URL::to('/appointment/index') }}">
                    Wszystkie wizyty
                </a>
            </li>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Wizyta w - <strong>{{$property->name}}</strong></h2>
        </div>
        <p>Wizyta dnia: <strong>{{$day}} {{$month}} {{$year}}</strong></p>
        <p>Godzina wizyty: <strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
        <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}</strong></p>
        <p>Całkowity czas wizyty: <strong>{{$appointment->minutes}} minut</strong></p>
        <p>Rodzaj wizyty: <strong>{{$item->name}}</strong></p>
        <p>Opis wizyty: <strong>{{$item->description}}</strong></p>
        <p>Cena: <strong>{{$item->price}} zł</strong></p>
        <p>Wykonawca: 
            <a href="{{ URL::to('employee/' . $employee->slug) }}">
                <strong>{{$employee->name}}</strong>
            </a>
        </p>
    </div>    
</div>
@endsection