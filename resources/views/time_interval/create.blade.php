@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    Back to Day
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create time interval</h1>

    {{ Form::open(['action' => 'TimeIntervalController@store', 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('start_time', 'Start (what time)') }}
            {{ Form::time('start_time', Input::old('start_time'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('end_time', 'End (what time)') }}
            {{ Form::time('end_time', Input::old('end_time'), array('class' => 'form-control')) }}
        </div>
        @if ($day)
            {{ Form::hidden('day_id', $day->id) }}
        @else
            {{ Form::hidden('day_id', Input::old('day_id')) }}
        @endif
        {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection