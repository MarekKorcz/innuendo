@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        {{ Form::open(['action' => 'AdminController@bossStore', 'method' => 'POST']) }}
    
            <h2 class="text-center">@lang('common.create_boss_account')</h2>

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="surname">@lang('common.surname')</label>
                {{ Form::text('surname', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="boss_email">@lang('common.email_address')</label>
                {{ Form::text('boss_email', Input::old('boss_email'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="boss_phone_number">@lang('common.phone_number')</label>
                {{ Form::number('boss_phone_number', Input::old('boss_phone_number'), array('class' => 'form-control')) }}
            </div>

            <h3 class="text-center">@lang('common.create_first_property')</h3>

            <div class="form-group">
                <label for="property_name">@lang('common.property_name')</label>
                {{ Form::text('property_name', Input::old('property_name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="street">@lang('common.street')</label>
                {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="street_number">@lang('common.street_number')</label>
                {{ Form::text('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="house_number">@lang('common.house_number')</label>
                {{ Form::text('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
            </div>

            <input type="submit" value="@lang('common.create')" class="btn btn-primary">

        {{ Form::close() }}
    </div>
</div>
@endsection