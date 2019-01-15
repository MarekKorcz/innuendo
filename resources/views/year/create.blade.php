@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    Back to Property
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create a Year</h1>

    {{ Form::open(['action' => 'YearController@store', 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('year', 'Year') }}
            {{ Form::number('year', Input::old('year'), array('class' => 'form-control')) }}
        </div>
        @if ($calendar)
            {{ Form::hidden('calendar_id', $calendar->id) }}
        @else
            {{ Form::hidden('calendar_id', Input::old('calendar_id')) }}
        @endif
        {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection