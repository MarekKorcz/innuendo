@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron" style="margin-top: 15px;">
                <form action="{{ action([App\Http\Controllers\PropertyController::class, 'invoiceDataStore']) }}" method="POST">
                    @csrf

                    <h2 class="text-center" style="padding-bottom: 1rem;">
                        @lang('common.create_invoice_data')
                        @lang('common.in')
                        {{$property->name}}
                    </h2>

                    <div class="form-group">
                        <label for="company_name">@lang('common.company_name'):</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number'):</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="nip"> NIP</label>:
                        <input type="text" name="nip" value="{{ old('nip') }}" class="form-control">
                        <div class="warning"></div>
                    </div>

                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <div class="text-center">
                        <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                    </div>
                </form>

            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
