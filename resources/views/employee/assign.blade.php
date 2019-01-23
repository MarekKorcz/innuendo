@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    Go Back
                </a>
            </li>
        </ul>
    </nav>

    <h1>Assign an Employee</h1>

    {{ Form::open(['action' => 'EmployeeController@store', 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('employee', 'Employee') }}
            {{ Form::select('employee', $employees, null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('slug', 'Slug') }}
            {{ Form::text('slug', Input::old('slug'), array('class' => 'form-control')) }}
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