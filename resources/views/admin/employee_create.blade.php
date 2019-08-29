@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        {{ Form::open(['action' => 'AdminController@employeeAdd', 'method' => 'POST']) }}
        
            <h2 class="text-center">@lang('common.create_employee_account')</h2>

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="surname">@lang('common.surname')</label>
                {{ Form::text('surname', Input::old('surname'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="email">@lang('common.email_address')</label>
                {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                <label for="phone_number">@lang('common.phone_number')</label>
                {{ Form::number('phone_number', Input::old('phone_number'), array('class' => 'form-control')) }}
            </div>

            <input type="submit" value="@lang('common.create')" class="btn btn-primary">

        {{ Form::close() }}
    </div>
</div>
@endsection