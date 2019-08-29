@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    @lang('common.back_to_month')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.create_days')</h1>

    {{ Form::open(['action' => 'DayController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="start_day">@lang('common.start_day')</label>
            {{ Form::number('start_day', Input::old('start_day'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="end_day">@lang('common.end_day')</label>
            {{ Form::number('end_day', Input::old('end_day'), array('class' => 'form-control')) }}
        </div>
        @if ($month)
            {{ Form::hidden('month_id', $month->id) }}
        @else
            {{ Form::hidden('month_id', Input::old('month_id')) }}
        @endif
        
        <input type="submit" value="@lang('common.create')" class="btn btn-primary">

    {{ Form::close() }}

</div>
@endsection