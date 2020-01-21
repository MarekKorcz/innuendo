@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding: 2rem 0 2rem 0;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::to('day/show/' . $day->id) }}">
                @lang('common.back_to_day')
            </a>
        </div>
    </div>

    <div class="jumbotron">
        <div class="text-center">
            <h1>@lang('common.create_graphic')</h1>
        </div>

        {{ Form::open(['action' => 'GraphicController@store', 'method' => 'POST']) }}

            <div class="form-group">
                <label for="start_time">@lang('common.start_time')</label>
                {{ Form::time('start_time', Input::old('start_time'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="end_time">@lang('common.end_time')</label>
                {{ Form::time('end_time', Input::old('end_time'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="employee_id">@lang('common.employees'):</label>
                <select name="employee_id" class="form-control">
                    @if (count($employees) > 0)
                        @foreach ($employees as $key => $employee)
                            @if ($key == 0)
                                <option value="{{$employee->id}}" selected="true">{{$employee->name}} {{$employee->surname}}</option>
                            @else
                                <option value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
            @if ($day)
                {{ Form::hidden('day_id', $day->id) }}
            @else
                {{ Form::hidden('day_id', Input::old('day_id')) }}
            @endif

            <div class="text-center" style="padding-top: 1rem;">
                <input type="submit" value="@lang('common.create')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection