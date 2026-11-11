@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron" style="margin-top: 15px;">
                <form action="{{ action([App\Http\Controllers\PropertyController::class, 'invoiceDataUpdate']) }}" method="POST">
                    @csrf

                    <h2 class="text-center" style="padding-bottom: 1rem;">
                        @lang('common.edit_invoice_data')
                        @lang('common.in')
                        {{$property->name}}
                    </h2>

                    <div class="form-group">
                        <label for="company_name">@lang('common.company_name'):</label>
                        <input type="text" name="company_name" value="{{ $invoiceData->company_name }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        <input type="email" name="email" value="{{ $invoiceData->email }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number'):</label>
                        <input type="text" name="phone_number" value="{{ $invoiceData->phone_number }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="nip"> NIP</label>:
                        <input type="text" name="nip" value="{{ $invoiceData->nip }}" class="form-control">
                        <div class="warning"></div>
                    </div>

                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
