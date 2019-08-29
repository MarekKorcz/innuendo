@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::previous() }}">
                    @lang('common.back_to_day')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.create_graphic')</h1>

    {{ Form::open(['action' => 'GraphicController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="start_time">@lang('common.start_time')</label>
            {{ Form::time('start_time', Input::old('start_time'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="end_time">@lang('common.end_time')</label>
            {{ Form::time('end_time', Input::old('end_time'), array('class' => 'form-control')) }}
        </div>
        @if ($day)
            {{ Form::hidden('day_id', $day->id) }}
        @else
            {{ Form::hidden('day_id', Input::old('day_id')) }}
        @endif
        <input type="submit" value="@lang('common.create')" class="btn btn-primary">

    {{ Form::close() }}

</div>
@endsection