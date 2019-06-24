@extends('layouts.app')
@section('content')

{!! Html::script('js/property_invoice_data.js') !!}

<div class="container" style="padding: 2rem;">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Przypisz dane do faktury do {{$property->name}}</h3>
                </div>

                <div class="card-body">
                    {{ Form::open(['id' => 'property-invoice-data', 'action' => 'BossController@invoiceDataStore', 'method' => 'POST']) }}
    
                        <div class="form-group">
                            {{ Form::label('website', 'Adres strony internetowej firmy') }}
                            {{ Form::text('website', Input::old('website'), array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('email', 'Email') }}
                            {{ Form::email('email', Input::old('email'), array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('nip', ' NIP') }}
                            {{ Form::text('nip', Input::old('nip'), array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('bank_name', 'Nazwa banku') }}
                            {{ Form::text('bank_name', Input::old('bank_name'), array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('account_number', 'Numer konta') }}
                            {{ Form::text('account_number', Input::old('account_number'), array('class' => 'form-control')) }}
                            <div class="warning"></div>
                        </div>
                        
                        <input type="hidden" id="substart_id" name="substart_id" value="{{$substart->id}}">

                        {{ Form::submit('Dodaj', array('class' => 'btn btn-primary')) }}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
