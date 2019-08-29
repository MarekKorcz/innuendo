@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    @lang('common.go_back')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.assign_an_employee')</h1>

    {{ Form::open(['action' => 'EmployeeController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="employee">@lang('common.employee')</label>
            {{ Form::select('employee', $employees, null, array('class' => 'form-control')) }}
        </div>
    
        @if ($calendar)
            {{ Form::hidden('calendar_id', $calendar->id) }}
        @else
            {{ Form::hidden('calendar_id', Input::old('calendar_id')) }}
        @endif
        
        <input type="submit" value="@lang('common.assign')" class="btn btn-primary">

    {{ Form::close() }}

</div>
@endsection