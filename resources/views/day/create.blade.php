@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                @lang('common.back_to_month')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h2>@lang('common.create_days')</h2>
    </div>

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
        
        <div class="text-center" style="padding-bottom: 2rem;">
            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </div>

    {{ Form::close() }}

</div>
@endsection