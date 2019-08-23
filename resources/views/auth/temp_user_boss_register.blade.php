@extends('layouts.app')
@section('content')

{!! Html::script('js/temp_user_boss_register.js') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('common.register')</h3>
                </div>

                <div class="card-body">
                    {{ Form::open(['id' => 'temp-boss-register', 'action' => 'Auth\RegisterController@tempUserBossRegistrationStore', 'method' => 'POST']) }}
    
                        <h2 class="text-center">@lang('common.register')</h2>

                        <div class="form-group">
                            <label for="name">@lang('common.name')</label>
                            {{ Form::text('name', $tempUser->name, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="surname">@lang('common.surname')</label>
                            {{ Form::text('surname', $tempUser->surname, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="boss_email">@lang('common.email_address')</label>
                            {{ Form::text('boss_email', $tempUser->email, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="boss_phone_number">@lang('common.phone_number')</label>
                            {{ Form::number('boss_phone_number', $tempUser->phone_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('common.password')</label>
                            {{ Form::password('password', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">@lang('common.password_confirm')</label>
                            {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>

                        <h3 class="text-center">@lang('common.create_first_property')</h3>

                        <div class="form-group">
                            <label for="property_name">@lang('common.company_name')</label>
                            {{ Form::text('property_name', $tempProperty->name, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="street">@lang('common.street')</label>
                            {{ Form::text('street', $tempProperty->street, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="street_number">@lang('common.street_number')</label>
                            {{ Form::text('street_number', $tempProperty->street_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="house_number">@lang('common.house_number')</label>
                            {{ Form::text('house_number', $tempProperty->house_number, array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        
                        <input type="hidden" id="register_code" name="register_code" value="{{$registerCode}}">

                        <input type="submit" value="@lang('common.create')" class="btn btn-primary">

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
