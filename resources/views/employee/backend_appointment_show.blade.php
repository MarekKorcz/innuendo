@extends('layouts.app')

@section('content')

{!! Html::script('js/backend_appointment_show.js') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="{{ URL::to('/employee/backend-calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-success">
                Powrót do kalendarza
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li>
                {!!Form::open(['action' => ['UserController@appointmentDestroy', $appointment->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Odwołaj wizytę', ['class' => 'btn btn-danger']) }}
                {!!Form::close()!!}
                <a class="btn btn-dark" href="{{ URL::to('/employee/backend-appointment/edit/' . $appointment->id) }}">
                    Edytuj wizyte
                </a>
                <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/index/' . $appointment->user->id) }}">
                    Wszystkie wizyty danego usera
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
        <p>Całkowity czas wizyty: <strong>{{$appointment->minutes}} minut</strong></p>
        <p>Rodzaj wizyty: <strong>{{$appointment->item->name}}</strong></p>
        <p>Opis wizyty: <strong>{{$appointment->item->description}}</strong></p>
        <p>Cena: <strong>{{$appointment->item->price}} zł</strong></p>
        <p>Wykonawca: <strong>{{$employee->name}}</strong></p>
        <p>
            Status wizyty: <strong id="status">{{config('appointment-status.' . $appointment->status)}}</strong>
        </p>
        <select id="appointment-status" class="form-control">
            @foreach ($statuses as $status)
                @if ($status['isActive'])
                    <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}" selected="selected">{{$status['value']}}</option>
                @else
                    <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}">{{$status['value']}}</option>
                @endif
            @endforeach
        </select>
    </div>    
</div>
@endsection