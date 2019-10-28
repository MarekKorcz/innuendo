@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h1>@lang('common.edit_temporary_property')</h1>

        {{ Form::open(['action' => ['PropertyController@tempPropertyUpdate', $tempProperty->id], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                {{ Form::text('name', $tempProperty->name, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="street">@lang('common.street')</label>
                {{ Form::text('street', $tempProperty->street, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="street_number">@lang('common.street_number')</label>
                {{ Form::text('street_number', $tempProperty->street_number, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="house_number">@lang('common.house_number')</label>
                {{ Form::text('house_number', $tempProperty->house_number, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="city">@lang('common.city')</label>
                {{ Form::text('city', $tempProperty->city, array('class' => 'form-control')) }}
            </div>

            {{ Form::hidden('_method', 'PUT') }}
           
            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection