@extends('layouts.app')
@section('content')

<script src="{{ asset('js/property_invoice_data.js') }}" defer></script>

<div class="container" style="padding: 2rem;">
    <div class="jumbotron">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">

            <div class="text-center">
                <h3>
                    @lang('common.add_invoice_data_to')
                    <strong>
                        {{$property->name}}
                    </strong>
                </h3>
            </div>

            <form id="property-invoice-data" action="{{ action([\App\Http\Controllers\BossController::class, 'invoiceDataStore']) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="company_name">@lang('common.company_name'):</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" class="form-control">
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="email">@lang('common.email_address'):</label>
                    <input type="email" id="email" name="email" value="{{ old('email_address') }}" class="form-control">
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="phone_number">@lang('common.phone_number'):</label>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" class="form-control">
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="nip"> NIP</label>:
                    <input type="text" id="nip" name="nip" value="{{ old('nip') }}" class="form-control">
                    <div class="warning"></div>
                </div>
            <!--    <div class="form-group">
                    <label for="bank_name">Nazwa banku</label>
                    <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" class="form-control">
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="account_number">Numer konta</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" class="form-control">
                    <div class="warning"></div>
                </div>-->

                <input type="hidden" id="property_id" name="property_id" value="{{$property->id}}">

                <div class="text-center">
                    <input type="submit" value="@lang('common.add')" class="btn pallet-1-3" style="color: white;">
                </div>
            </form>

        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
