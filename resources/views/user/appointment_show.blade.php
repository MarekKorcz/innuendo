@extends('layouts.app')
@section('content')

{!! Html::style('css/appointment_show.css') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="{{ URL::to('/employee/calendar/'. $calendarId . '/' . $year . '/' . $month_number . '/' . $day) }}" class="btn btn-success">
                Powrót do kalendarza
            </a>
        </div>
        <ul class="nav navbar-nav">
            @if ($canBeDeleted)
                <li>                
                    <a href="#deleteAnAppointment" data-toggle="modal" class="btn btn-danger">
                        Usuń wizytę
                    </a>
                </li>
            @endif
            <li>
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
        <p>Rodzaj wizyty: <strong>{{$appointment->item->name}}</strong></p>
        <p>Opis wizyty: <strong>{{$appointment->item->description}}</strong></p>
        <p>Cena: <strong>{{$appointment->item->price}} zł</strong></p>
        <p>Wykonawca: 
            <a href="{{ URL::to('employee/' . $employee->slug) }}">
                <strong>{{$employee->name}}</strong>
            </a>
        </p>
        <p>
            Status wizyty: <strong>{{config('appointment-status.' . $appointment->status)}}</strong>
        </p>
        @if ($subscription)
            <p>Subskrypcja: 
                <a href="{{ URL::to('user/subscription/purchased/show/' . $appointment->purchase->id) }}">
                    <strong>{{$subscription->name}}</strong>
                </a>
            </p>
        @endif
    </div>
    
    <div class="modal hide" id="deleteAnAppointment">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Usunięcie wizyty</h3>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p>Jeśli odwołasz wizytę w okresie ją poprzedzającym, mniejszym niż jedna doba, to wizyta 
                ta zostanie uznana za wykonaną i nie będzie możliwości wykorzystania jej w przyszłości!!</p>
                
                <div class="row">
                    <div class="col-6">
                        <a class="btn btn-success" data-dismiss="modal" href="#">Wróć</a>
                    </div>
                    <div class="col-6" style="text-align: right;">
                        {!!Form::open(['action' => ['UserController@appointmentDestroy', $appointment->id], 'method' => 'POST'])!!}
                            {{ Form::hidden('_method', 'DELETE') }}
                            {{ Form::submit('Odwołaj wizytę', ['class' => 'btn btn-danger']) }}
                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection