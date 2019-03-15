@extends('layouts.app')
@section('content')

{!! Html::script('js/subscription_create.js') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/employee/calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-primary">
                    Powrót do kalendarza
                </a>
            </li>
        </ul>
    </nav>
    
    <h1 style="padding-top: 30px;">Zarezerwuj wizytę</h1>

    {{ Form::open(['id' => 'appointment-create', 'action' => 'AppointmentController@store', 'method' => 'POST']) }}

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
    
        <div class="form-group">
            {{ Form::label('item', 'Rodzaj zabiegu:') }}
            <select id="item" name="item" class="form-control">
                <option disabled selected value> --- wybierz rodzaj zabiegu --- </option>
                @foreach ($items as $item)
                    @if ($item->subscription_name)
                        <option value="{{$item->id}}" data-subscription_id="{{$item->subscription_id}}">{{$item->name}} - {{$item->minutes}} minut - {{$item->price}} zł - {{$item->subscription_name}}</option>
                    @else
                        <option value="{{$item->id}}">{{$item->name}} - {{$item->minutes}} minut - {{$item->price}} zł</option>
                    @endif
                @endforeach
            </select>
            <div id="item-warning"></div>
        </div>
    
        <div id="subscription"></div>        
    
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
        
        {{ Form::submit('Zarezerwuj', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@endsection