@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::previous() }}" class="btn btn-primary">
                    Wróć
                </a>
            </li>
        </ul>
    </nav>

    <h1>Edytuj wizytę</h1>

    {{ Form::open(['action' => ['WorkerController@appointmentUpdate', $appointment->id], 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('start_time', 'Godzina rozpoczęcia') }}
            {{ Form::time('start_time', $appointment->start_time, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('end_time', 'Godzina zakończenia') }}
            {{ Form::time('end_time', $appointment->end_time, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="item">Rodzaj wizyty: </label>
            <select name="item" class="form-control">
                @foreach ($items as $item)
                    @if ($item['isActive'])
                        <option value="{{$item['key']}}" selected="selected">{{$item['value']}}</option>
                    @else
                        <option value="{{$item['key']}}">{{$item['value']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="item">Status: </label>
            <select id="appointment-status" name="status" class="form-control">
                @foreach ($statuses as $status)
                    @if ($status['isActive'])
                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}" selected="selected">{{$status['value']}}</option>
                    @else
                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}">{{$status['value']}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    
        @if ($appointment->id)
            {{ Form::hidden('appointmentId', $appointment->id) }}
        @else
            {{ Form::hidden('appointmentId', Input::old('appointmentId')) }}
        @endif
        
        {{ Form::hidden('_method', 'PUT') }}
        
        {{ Form::submit('Zaktualizuj', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection