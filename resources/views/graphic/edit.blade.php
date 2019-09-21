@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse" style="padding: 1rem 0 1rem 0;">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/day/show/' . $day->id) }}" class="btn btn-primary">
                    @lang('common.back_to_day')
                </a>
            </li>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="text-center">
            <h1>@lang('common.edit_schedule')</h1>
        </div>

        {{ Form::open(['action' => ['GraphicController@update'], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="start_time">@lang('common.start_time')</label>
                <input class="form-control" type="time" name="start_time" value="{{$graphic->start_time}}">
            </div>
            <div class="form-group">
                <label for="end_time">@lang('common.end_time')</label>
                <input class="form-control" type="time" name="end_time" value="{{$graphic->end_time}}">
            </div>

            {{ Form::hidden('graphic_id', $graphic->id) }}
        
            {{ Form::hidden('_method', 'PUT') }}

            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection