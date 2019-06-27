@extends('layouts.app')
@section('content')

{!! Html::script('js/property_invoice_data.js') !!}

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        <h1 class="text-center">Edytuj fakture</h1>

        {{ Form::open(['id' => 'property-invoice-data', 'action' => ['BossController@invoiceDataUpdate'], 'method' => 'POST']) }}
                
            <div class="form-group">
                {{ Form::label('company_name', 'Nazwa firmy') }}
                {{ Form::text('company_name', $invoiceData->company_name, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('email', 'Email') }}
                {{ Form::email('email', $invoiceData->email, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('phone_number', 'Numer telefonu') }}
                {{ Form::text('phone_number', $invoiceData->phone_number, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('nip', ' NIP') }}
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
            
            {{ Form::submit('Aktualizuj', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    </div>
</div>
@endsection