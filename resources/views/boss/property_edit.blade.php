@extends('layouts.app')
@section('content')

<script src="{{ asset('js/boss_property_edit.js') }}" defer></script>

<div class="container">

    <div class="row text-center" style="padding: 1rem 0 1rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/boss/subscription/list/' . $property->id . '/0') }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.go_back')
            </a>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>

    <div class="jumbotron" style="margin: 15px;">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">

                <div class="text-center">
                    <h1>@lang('navbar.property_edit')</h1>
                </div>

                <form id="property-edit" action="{{ action([\App\Http\Controllers\BossController::class, 'propertyUpdate']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">@lang('common.company_name'):</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $property->name) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street">@lang('common.street'):</label>
                        <input type="text" id="street" name="street" value="{{ old('street', $property->street) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street_number">@lang('common.street_number'):</label>
                        <input type="text" id="street_number" name="street_number" value="{{ old('street_number', $property->street_number) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="house_number">@lang('common.house_number'):</label>
                        <input type="text" id="house_number" name="house_number" value="{{ old('house_number', $property->house_number) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="city">@lang('common.city'):</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $property->city) }}" class="form-control">
                        <div class="warning"></div>
                    </div>

                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <div class="text-center" style="padding-top: 1rem;">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>
                </form>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection
