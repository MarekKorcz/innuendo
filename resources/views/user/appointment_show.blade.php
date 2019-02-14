@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="{{ URL::to('/employee/calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-success">
                Powrót do kalendarza
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li>
                {!!Form::open(['action' => ['UserController@appointmentDestroy', $appointment->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Odwołaj wizytę', ['class' => 'btn btn-danger']) }}
                {!!Form::close()!!}
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
        <p>
            Status: 
            <strong>
                @if ($appointment->status == 0)
                    Oczekujący
                @elseif ($appointment->status == 1)
                    Wykonany
                @elseif ($appointment->status == 2)
                    Odwołany
                @elseif ($appointment->status == 3)
                    Opuszczony
                @endif
            </strong>
        </p>
    </div>    
</div>
@endsection