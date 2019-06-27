@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        {{ Form::open(['action' => 'AdminController@bossStore', 'method' => 'POST']) }}
    
            <h2 class="text-center">Create Boss account</h2>

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('surname', 'Surname') }}
                {{ Form::text('surname', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('boss_email', 'Email') }}
                {{ Form::text('boss_email', Input::old('boss_email'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('boss_phone_number', 'Phone number') }}
                {{ Form::number('boss_phone_number', Input::old('boss_phone_number'), array('class' => 'form-control')) }}
            </div>

            <h3 class="text-center">Create property</h3>

            <div class="form-group">
                {{ Form::label('property_name', 'Property name') }}
                {{ Form::text('property_name', Input::old('property_name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('street', 'Street') }}
                {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('street_number', 'Street number') }}
                {{ Form::text('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('house_number', 'House number') }}
                {{ Form::text('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
            </div>

            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    </div>
</div>
@endsection