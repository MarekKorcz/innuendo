@extends('layouts.app')
@section('content')

{!! Html::script('js/property_invoice_data.js') !!}

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                
                <h1 class="text-center">@lang('common.edit_invoice_data')</h1>

                {{ Form::open(['id' => 'property-invoice-data', 'action' => ['BossController@invoiceDataUpdate'], 'method' => 'POST']) }}

                    <div class="form-group">
                        <label for="company_name">@lang('common.company_name'):</label>
                        {{ Form::text('company_name', $invoiceData->company_name, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        {{ Form::email('email', $invoiceData->email, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number'):</label>
                        {{ Form::text('phone_number', $invoiceData->phone_number, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('nip', 'NIP') }}:
                        {{ Form::text('nip', $invoiceData->nip, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
        <!--            <div class="form-group">
                        {{ Form::label('bank_name', 'Nazwa banku') }}
                        {{ Form::text('bank_name', $invoiceData->bank_name, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('account_number', 'Numer konta') }}
                        {{ Form::text('account_number', $invoiceData->account_number, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>-->

                    {{ Form::hidden('invoice_data_id', $invoiceData->id) }}
                    {{ Form::hidden('substart_id', $substart->id) }}
                    {{ Form::hidden('_method', 'PUT') }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection