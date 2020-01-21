@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-success" href="{{ URL::to('/property/' . $property->id) }}">
                @lang('common.back_to_property')
            </a>
        </div>
    </div>

    <div class="text-center" style="padding: 1rem;">
        <h1>@lang('common.create_year')</h1>

        {{ Form::open(['action' => 'YearController@store', 'method' => 'POST']) }}

            <div class="form-group">
                <label for="year">@lang('common.year')</label>
                {{ Form::number('year', Input::old('year'), array('class' => 'form-control')) }}
            </div>
            @if ($property !== null)
                {{ Form::hidden('property_id', $property->id) }}
            @else
                {{ Form::hidden('property_id', Input::old('property_id')) }}
            @endif

            <input type="submit" value="@lang('common.create')" class="btn btn-primary">

        {{ Form::close() }}
    </div>

</div>
@endsection