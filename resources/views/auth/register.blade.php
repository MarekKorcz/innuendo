@extends('layouts.app')

@section('content')

{!! Html::script('js/register.js') !!}
{!! Html::style('css/register.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('common.register')</h3>
                </div>

                <div class="card-body">
                    <form id="register" method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">@lang('common.name') :</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="name-error"></div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">@lang('common.surname') :</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autofocus>

                                @if ($errors->has('surname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="surname-error"></div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">@lang('common.phone_number') :</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" name="phone_number" value="{{ old('phone_number') }}" required autofocus>

                                @if ($errors->has('phone_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('phone_number') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="phone_number-error"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">@lang('common.email_address') :</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="email-error"></div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="code" class="col-md-4 col-form-label text-md-right">@lang('common.registration_code') :</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" required>
                                
                                @if ($errors->has('code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="code-error"></div>
                                <div id="code-data"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">@lang('common.password') :</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                
                                <div id="password-error"></div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">@lang('common.password_confirm') :</label>

                            <div class="col-md-6">
                                <input id="password_confirm" type="password" class="form-control" name="password_confirm" required>
                                
                                <div id="password_confirm-error"></div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="register-worker" type="submit" class="btn btn-primary">
                                    @lang('common.register')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal hide" id="registerNewBoss">
        <div class="modal-content">
            <div class="modal-body">         
                {{ Form::open(['id' => 'register-boss', 'action' => 'Auth\RegisterController@registerNewBoss', 'method' => 'POST']) }}

                    <h3 class="text-center">@lang('common.report_your_company')</h3>

                    <div class="form-group">
                        <label for="property_name">@lang('common.company_name') :</label>
                        {{ Form::text('property_name', Input::old('property_name'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street">@lang('common.street') :</label>
                        {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street_number">@lang('common.street_number') :</label>
                        {{ Form::text('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="house_number">@lang('common.house_number') :</label>
                        {{ Form::text('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="city">@lang('common.city') :</label>
                        {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>

                    <div class="text-center">
                        <input type="submit" value="@lang('common.create')" class="btn btn-primary">
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
