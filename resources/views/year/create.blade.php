@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    @lang('common.back_to_property')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.create_year')</h1>

    {{ Form::open(['action' => 'YearController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="year">@lang('common.year')</label>
            {{ Form::number('year', Input::old('year'), array('class' => 'form-control')) }}
        </div>
        @if ($calendar)
            {{ Form::hidden('calendar_id', $calendar->id) }}
        @else
            {{ Form::hidden('calendar_id', Input::old('calendar_id')) }}
        @endif
        
        <input type="submit" value="@lang('common.create')" class="btn btn-primary">

    {{ Form::close() }}

</div>
@endsection