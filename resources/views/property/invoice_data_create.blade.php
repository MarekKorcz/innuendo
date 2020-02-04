@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron" style="margin-top: 15px;">
                {{ Form::open(['action' => 'PropertyController@invoiceDataStore', 'method' => 'POST']) }}

                    <h2 class="text-center" style="padding-bottom: 1rem;">
                        @lang('common.create_invoice_data')
                        @lang('common.in')
                        {{$property->name}}
                    </h2>

                    <div class="form-group">
                        <label for="company_name">@lang('common.company_name'):</label>
                        {{ Form::text('company_name', Input::old('company_name'), array('class' => 'form-control')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        {{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
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
                
                    {{ Form::hidden('property_id', $property->id) }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection