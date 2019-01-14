@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/property/index') }}" class="btn btn-primary">
                    View All Properties
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create a Property</h1>

    {{ Form::open([
        'action' => 'PropertyController@store',
        'method' => 'POST'
    ]) }}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', Input::old('description'), array('id' => 'article-ckeditor', 'class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('phone_number', 'Phone number') }}
            {{ Form::number('phone_number', Input::old('phone_number'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('street', 'Street') }}
            {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('street_number', 'Street number') }}
            {{ Form::number('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('house_number', 'House number') }}
            {{ Form::number('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('city', 'City') }}
            {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
        </div>

        {{ Form::submit('Create the Property!', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection