@extends('layouts.app')
@section('content')

{!! Html::script('js/property_invoice_data.js') !!}

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

            {{ Form::open(['id' => 'property-invoice-data', 'action' => 'BossController@invoiceDataStore', 'method' => 'POST']) }}

                <div class="form-group">
                    <label for="company_name">@lang('common.company_name'):</label>
                    {{ Form::text('company_name', Input::old('company_name'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="email">@lang('common.email_address'):</label>
                    {{ Form::email('email', Input::old('email_address'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    <label for="phone_number">@lang('common.phone_number'):</label>
                    {{ Form::text('phone_number', Input::old('phone_number'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    {{ Form::label('nip', ' NIP') }}:
                    {{ Form::text('nip', Input::old('nip'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>
<!--                        <div class="form-group">
                    {{ Form::label('bank_name', 'Nazwa banku') }}
                    {{ Form::text('bank_name', Input::old('bank_name'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>
                <div class="form-group">
                    {{ Form::label('account_number', 'Numer konta') }}
                    {{ Form::text('account_number', Input::old('account_number'), array('class' => 'form-control')) }}
                    <div class="warning"></div>
                </div>-->

                <input type="hidden" id="property_id" name="property_id" value="{{$property->id}}">

                <div class="text-center">
                    <input type="submit" value="@lang('common.add')" class="btn pallet-1-3" style="color: white;">
                </div>

            {{ Form::close() }}
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
