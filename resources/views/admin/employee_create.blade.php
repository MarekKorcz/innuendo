@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        {{ Form::open(['action' => 'AdminController@employeeAdd', 'method' => 'POST']) }}
        
            <h2 class="text-center">Create Employee account</h2>

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('surname', 'Surname') }}
                {{ Form::text('surname', Input::old('surname'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('phone_number', 'Phone number') }}
                {{ Form::number('phone_number', Input::old('phone_number'), array('class' => 'form-control')) }}
            </div>

            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    </div>
</div>
@endsection