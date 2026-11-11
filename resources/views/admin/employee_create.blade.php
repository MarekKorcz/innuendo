@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <form action="{{ action([\App\Http\Controllers\AdminController::class, 'employeeAdd']) }}" method="POST">
            @csrf

            <h2 class="text-center">@lang('common.create_employee_account'):</h2>

            <div class="form-group">
                <label for="name">@lang('common.name'):</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="surname">@lang('common.surname'):</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">@lang('common.email_address'):</label>
                <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="phone_number">@lang('common.phone_number'):</label>
                <input type="number" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="form-control">
            </div>

            <div class="text-center">
                <input type="submit" value="@lang('common.create')" class="btn pallet-2-4" style="color: white;">
            </div>
        </form>
    </div>
</div>
@endsection
