@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <form action="{{ action([\App\Http\Controllers\AdminController::class, 'bossStore']) }}" method="POST">
            @csrf

            <h2 class="text-center" style="padding-bottom: 1rem;">@lang('common.create_boss_account')</h2>

            <div class="form-group">
                <label for="name">@lang('common.name'):</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="surname">@lang('common.surname'):</label>
                <input type="text" name="surname" id="surname" value="{{ old('surname') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="boss_email">@lang('common.email_address'):</label>
                <input type="text" name="boss_email" id="boss_email" value="{{ old('boss_email') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="boss_phone_number">@lang('common.phone_number'):</label>
                <input type="number" name="boss_phone_number" id="boss_phone_number" value="{{ old('boss_phone_number') }}" class="form-control">
            </div>

            <h3 class="text-center" style="padding: 1rem 0 1rem 0;">@lang('common.create_first_property')</h3>

            <div class="form-group">
                <label for="property_name">@lang('common.property_name'):</label>
                <input type="text" name="property_name" id="property_name" value="{{ old('property_name') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="street">@lang('common.street'):</label>
                <input type="text" name="street" id="street" value="{{ old('street') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="street_number">@lang('common.street_number'):</label>
                <input type="text" name="street_number" id="street_number" value="{{ old('street_number') }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="house_number">@lang('common.house_number'):</label>
                <input type="text" name="house_number" id="house_number" value="{{ old('house_number') }}" class="form-control">
            </div>

            <div class="text-center">
                <input type="submit" value="@lang('common.create')" class="btn pallet-2-4" style="color: white;">
            </div>
        </form>
    </div>
</div>
@endsection
