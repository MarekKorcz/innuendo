@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron" style="margin-top: 15px;">
                {{ Form::open(['action' => 'PropertyController@invoiceDataUpdate', 'method' => 'POST']) }}

                    <h2 class="text-center" style="padding-bottom: 1rem;">
                        @lang('common.edit_invoice_data')
                        @lang('common.in')
                        {{$property->name}}
                    </h2>

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
                        {{ Form::label('nip', ' NIP') }}:
                        {{ Form::text('nip', $invoiceData->nip, array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                
                    {{ Form::hidden('property_id', $property->id) }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection