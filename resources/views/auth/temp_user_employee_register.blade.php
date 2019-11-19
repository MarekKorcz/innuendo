@extends('layouts.app')
@section('content')

{!! Html::script('js/temp_user_employee_register.js') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('common.register')</h3>
                </div>

                <div class="card-body">
                    {{ Form::open(['id' => 'temp-employee-register', 'action' => 'Auth\RegisterController@tempUserEmployeeRegistrationStore', 'method' => 'POST']) }}
    
                        <div class="form-group">
                            <label for="name">@lang('common.name'):</label>
                            {{ Form::text('name', $tempUser->name, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="surname">@lang('common.surname'):</label>
                            {{ Form::text('surname', $tempUser->surname, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">@lang('common.email_address'):</label>
                            {{ Form::text('email', $tempUser->email, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">@lang('common.phone_number'):</label>
                            {{ Form::number('phone_number', $tempUser->phone_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('common.password'):</label>
                            {{ Form::password('password', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">@lang('common.password_confirm'):</label>
                            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        
                        <input type="hidden" id="register_code" name="register_code" value="{{$registerCode}}">

                        <div class="text-center">
                            <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                        </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
