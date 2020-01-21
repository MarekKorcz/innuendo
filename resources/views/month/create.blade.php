@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                @lang('common.back_to_year')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h1>@lang('common.create_month')</h1>
    </div>

    {{ Form::open(['action' => 'MonthController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="month">@lang('common.month')</label>
            {{ Form::text('month', Input::old('month'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="month_en">@lang('common.month_en')</label>
            {{ Form::text('month_en', Input::old('month_en'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="month_number">@lang('common.month_number')</label>
            {{ Form::number('month_number', Input::old('month_number'), array('class' => 'form-control')) }}
        </div>
        @if ($year)
            {{ Form::hidden('year_id', $year->id) }}
        @else
            {{ Form::hidden('year_id', Input::old('year_id')) }}
        @endif
        
        <div class="text-center" style="padding-bottom: 1rem;">
            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </div>

    {{ Form::close() }}

</div>
@endsection