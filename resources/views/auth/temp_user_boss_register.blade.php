@extends('layouts.app')
@section('content')

{!! Html::script('js/temp_user_boss_register.js') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Zarejestruj się</h3>
                </div>

                <div class="card-body">
                    {{ Form::open(['id' => 'temp-boss-register', 'action' => 'Auth\RegisterController@tempUserBossRegistrationStore', 'method' => 'POST']) }}
    
                        <h2 class="text-center">Zarejestruj się</h2>

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
                            {{ Form::label('boss_email', 'Email') }}
                            {{ Form::text('boss_email', $tempUser->email, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('boss_phone_number', 'Numer telefonu') }}
                            {{ Form::number('boss_phone_number', $tempUser->phone_number, array('class' => 'form-control')) }}
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

                        <h3 class="text-center">Stwórz pierwszą lokalizację</h3>

                        <div class="form-group">
                            {{ Form::label('property_name', 'Nazwa lokalizacji') }}
                            {{ Form::text('property_name', $tempProperty->name, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('street', 'Ulica') }}
                            {{ Form::text('street', $tempProperty->street, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('street_number', 'Numer ulicy') }}
                            {{ Form::text('street_number', $tempProperty->street_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('house_number', 'Numer budynku') }}
                            {{ Form::text('house_number', $tempProperty->house_number, array('class' => 'form-control')) }}
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
