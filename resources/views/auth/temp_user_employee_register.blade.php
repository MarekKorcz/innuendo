@extends('layouts.app')
@section('content')

{!! Html::script('js/temp_user_employee_register.js') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Zarejestruj się</h3>
                </div>

                <div class="card-body">
                    {{ Form::open(['id' => 'temp-employee-register', 'action' => 'Auth\RegisterController@tempUserEmployeeRegistrationStore', 'method' => 'POST']) }}
    
                        <div class="form-group">
                            {{ Form::label('name', 'Imię') }}
                            {{ Form::text('name', $tempUser->name, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('surname', 'Nazwisko') }}
                            {{ Form::text('surname', $tempUser->surname, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email') }}
                            {{ Form::text('email', $tempUser->email, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('phone_number', 'Numer telefonu') }}
                            {{ Form::number('phone_number', $tempUser->phone_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('password', 'Hasło') }}
                            {{ Form::password('password', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('password_confirmation', 'Hasło (powtórz)') }}
                            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        
                        <input type="hidden" id="register_code" name="register_code" value="{{$registerCode}}">

                        {{ Form::submit('Stwórz', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
