@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    Back to Year
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create a Month</h1>

    {{ Form::open(['action' => 'MonthController@store', 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('month', 'Month') }}
            {{ Form::text('month', Input::old('month'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('month_number', 'Month number') }}
            {{ Form::number('month_number', Input::old('month_number'), array('class' => 'form-control')) }}
        </div>
        @if ($year)
            {{ Form::hidden('year_id', $year->id) }}
        @else
            {{ Form::hidden('year_id', Input::old('year_id')) }}
        @endif
        {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection