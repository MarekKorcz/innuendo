@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_property_edit.js') !!}

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        <h1>
            @lang('navbar.property_edit')
        </h1>

        {{ Form::open(['id' => 'property-edit', 'action' => ['BossController@propertyUpdate'], 'method' => 'POST']) }}

            <div class="form-group">
                {{ Form::label('name', 'Nazwa') }}
                {{ Form::text('name', $property->name, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="street">@lang('common.street')</label>
                {{ Form::text('street', $property->street, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="street_number">@lang('common.street_number')</label>
                {{ Form::text('street_number', $property->street_number, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="house_number">@lang('common.house_number')</label>
                {{ Form::text('house_number', $property->house_number, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="city">@lang('common.city')</label>
                {{ Form::text('city', $property->city, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
        
            {{ Form::hidden('property_id', $property->id) }}
            {{ Form::hidden('_method', 'PUT') }}
            
            <input type="submit" value="@lang('common.update')" class="btn btn-primary">

        {{ Form::close() }}
    </div>
</div>
@endsection