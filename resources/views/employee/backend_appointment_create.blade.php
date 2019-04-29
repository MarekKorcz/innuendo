@extends('layouts.app')

@section('content')

{!! Html::script('js/backend_appointment_create.js') !!}
{!! Html::style('css/backend_appointment_create.css') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/employee/backend-calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-primary">
                    Powrót do kalendarza
                </a>
            </li>
        </ul>
    </nav>
    
    <h1 style="padding-top: 30px;">Zarezerwuj wizytę</h1>

    {{ Form::open(['id' => 'appointment-create', 'action' => 'WorkerController@appointmentStore', 'method' => 'POST']) }}

        <div class="form-group">
            <p>
                Godzina wizyty: <strong style="font-size: 20px;">{{$appointmentTerm}}</strong>
            </p>
            @if ($appointmentTerm)
                {{ Form::hidden('appointmentTerm', $appointmentTerm) }}
            @else
                {{ Form::hidden('appointmentTerm', Input::old('appointmentTerm')) }}
            @endif
        </div>
    
        <div id="client">
            <div class="row">
                <div id="credential-1" class="col-8 col-sm-8 col-md-10 col-lg-10">
                    <div class="form-group">
                        <label for="search">Klient</label>
                        <input id="search" class="form-control" type="text" name="search" placeholder="Szukaj klienta" autocomplete="off">
                    </div>
                    <div class="warning"></div>
                    <ul id="result" class="list-group"></ul>
                    <input id="userId" type="hidden" name="userId" value="">
                </div>
                <div class="col-4 col-sm-4 col-md-2 col-lg-2">
                    <div class="text-center" style="padding: 3px;">
                        <label for="isNew">Nowy?</label><br>
                        <input id="isNew" type="checkbox" style="width: 30px; height: 30px;">
                    </div>
                </div>
            </div>            
        </div>
        
        <div id="items"></div>
    
        @if ($calendarId)
            {{ Form::hidden('calendarId', $calendarId) }}
        @else
            {{ Form::hidden('calendarId', Input::old('calendarId')) }}
        @endif
        
        @if ($graphicId)
            {{ Form::hidden('graphicId', $graphicId) }}
        @else
            {{ Form::hidden('graphicId', Input::old('graphicId')) }}
        @endif
        
        @if ($year)
            {{ Form::hidden('year', $year) }}
        @else
            {{ Form::hidden('year', Input::old('year')) }}
        @endif
        
        @if ($month)
            {{ Form::hidden('month', $month) }}
        @else
            {{ Form::hidden('month', Input::old('month')) }}
        @endif
        
        @if ($day)
            {{ Form::hidden('day', $day) }}
        @else
            {{ Form::hidden('day', Input::old('day')) }}
        @endif
        
        @if ($possibleAppointmentLengthInMinutes)
            {{ Form::hidden('possibleAppointmentLengthInMinutes', $possibleAppointmentLengthInMinutes) }}
        @else
            {{ Form::hidden('possibleAppointmentLengthInMinutes', Input::old('possibleAppointmentLengthInMinutes')) }}
        @endif
        
        @if ($propertyId)
            {{ Form::hidden('propertyId', $propertyId) }}
        @else
            {{ Form::hidden('propertyId', Input::old('propertyId')) }}
        @endif
        
        {{ Form::submit('Zarezerwuj', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@endsection