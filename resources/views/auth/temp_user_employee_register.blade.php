@extends('layouts.app')
@section('content')

<script src="{{ asset('js/temp_user_employee_register.js') }}"></script>

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>@lang('common.register')</h3>
                </div>

                <div class="card-body">
                    <form id="temp-employee-register" action="{{ action([\App\Http\Controllers\Auth\RegisterController::class, 'tempUserEmployeeRegistrationStore']) }}" method="POST">
                        @csrf

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
                            <label for="email">@lang('common.email_address'):</label>
                            <input type="text" id="email" name="email" value="{{ old('email', $tempUser->email) }}" class="form-control">
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            <label for="phone_number">@lang('common.phone_number'):</label>
                            <input type="number" id="phone_number" name="phone_number" value="{{ old('phone_number', $tempUser->phone_number) }}" class="form-control">
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
