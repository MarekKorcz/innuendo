@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    Back to Month
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create Days</h1>

    {{ Form::open(['action' => 'DayController@store', 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('start_day', 'Start day') }}
            {{ Form::number('start_day', Input::old('start_day'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('end_day', 'End day') }}
            {{ Form::number('end_day', Input::old('end_day'), array('class' => 'form-control')) }}
        </div>
        @if ($month)
            {{ Form::hidden('month_id', $month->id) }}
        @else
            {{ Form::hidden('month_id', Input::old('month_id')) }}
        @endif
        {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection