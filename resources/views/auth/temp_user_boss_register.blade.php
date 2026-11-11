@extends('layouts.app')
@section('content')

<script src="{{ asset('js/temp_user_boss_register.js') }}"></script>

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('common.register')</h3>
                </div>

                <div class="card-body">
                    <form id="temp-boss-register" action="{{ action([\App\Http\Controllers\Auth\RegisterController::class, 'tempUserBossRegistrationStore']) }}" method="POST">
                        @csrf

                        <h2 class="text-center">@lang('common.register')</h2>

                        <div class="form-group">
                            <label for="name">@lang('common.name'):</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $tempUser->name) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="surname">@lang('common.surname'):</label>
                            <input type="text" id="surname" name="surname" value="{{ old('surname', $tempUser->surname) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="boss_email">@lang('common.email_address'):</label>
                            <input type="text" id="boss_email" name="boss_email" value="{{ old('boss_email', $tempUser->email) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="boss_phone_number">@lang('common.phone_number'):</label>
                            <input type="number" id="boss_phone_number" name="boss_phone_number" value="{{ old('boss_phone_number', $tempUser->phone_number) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">@lang('common.password'):</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">@lang('common.password_confirm'):</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            <div class="warning"></div>
                        </div>

                        <h3 class="text-center">@lang('common.create_first_property')</h3>

                        <div class="form-group">
                            <label for="property_name">@lang('common.company_name'):</label>
                            <input type="text" id="property_name" name="property_name" value="{{ old('property_name', $tempProperty->name) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="street">@lang('common.street'):</label>
                            <input type="text" id="street" name="street" value="{{ old('street', $tempProperty->street) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="street_number">@lang('common.street_number'):</label>
                            <input type="text" id="street_number" name="street_number" value="{{ old('street_number', $tempProperty->street_number) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="house_number">@lang('common.house_number'):</label>
                            <input type="text" id="house_number" name="house_number" value="{{ old('house_number', $tempProperty->house_number) }}" class="form-control">
                            <div class="warning"></div>
                        </div>

                        <input type="hidden" id="register_code" name="register_code" value="{{$registerCode}}">

                        <div class="text-center">
                            <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
