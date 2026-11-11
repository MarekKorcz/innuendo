@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h1>@lang('common.edit_temporary_property')</h1>

        <form action="{{ action([App\Http\Controllers\PropertyController::class, 'tempPropertyUpdate'], [$tempProperty->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">@lang('common.name')</label>
                <input type="text" name="name" value="{{ $tempProperty->name }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="street">@lang('common.street')</label>
                <input type="text" name="street" value="{{ $tempProperty->street }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="street_number">@lang('common.street_number')</label>
                <input type="text" name="street_number" value="{{ $tempProperty->street_number }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="house_number">@lang('common.house_number')</label>
                <input type="text" name="house_number" value="{{ $tempProperty->house_number }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="city">@lang('common.city')</label>
                <input type="text" name="city" value="{{ $tempProperty->city }}" class="form-control">
            </div>

            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
            </div>
        </form>

    </div>
</div>
@endsection
