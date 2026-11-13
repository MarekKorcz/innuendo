@extends('layouts.app')
@section('content')

<script src="{{ asset('js/property_invoice_data.js') }}" defer></script>

<div class="container">

    <div class="row text-center" style="padding-top: 1rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/boss/invoices/in/' . $property->id) }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.invoices_in')
                {{$property->name}}
            </a>
        </div>
        <div class="col-4"></div>
    </div>

    <div class="jumbotron" style="margin: 15px;">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">

                <h1 class="text-center">
                    @lang('common.edit_invoice_data')
                    @lang('common.in')
                    {{$property->name}}
                </h1>

                <form id="property-invoice-data" action="{{ action([\App\Http\Controllers\BossController::class, 'invoiceDataUpdate']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="company_name">@lang('common.company_name'):</label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $invoiceData->company_name) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $invoiceData->email) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number'):</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $invoiceData->phone_number) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP</label>:
                        <input type="text" id="nip" name="nip" value="{{ old('nip', $invoiceData->nip) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                <!--    <div class="form-group">
                        <label for="bank_name">Nazwa banku</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $invoiceData->bank_name) }}" class="form-control">
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="account_number">Numer konta</label>
                        <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $invoiceData->account_number) }}" class="form-control">
                        <div class="warning"></div>
                    </div>-->

                    <input type="hidden" name="invoice_data_id" value="{{ $invoiceData->id }}">
                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>
                </form>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection
