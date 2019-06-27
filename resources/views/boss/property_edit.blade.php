@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_property_edit.js') !!}

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        <h1>Edytuj lokalizacje</h1>

        {{ Form::open(['id' => 'property-edit', 'action' => ['BossController@propertyUpdate'], 'method' => 'POST']) }}

            <div class="form-group">
                {{ Form::label('name', 'Nazwa') }}
                {{ Form::text('name', $property->name, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('street', 'Ulica') }}
                {{ Form::text('street', $property->street, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('street_number', 'Numer ulicy') }}
                {{ Form::text('street_number', $property->street_number, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('house_number', 'Numer domu') }}
                {{ Form::text('house_number', $property->house_number, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                {{ Form::label('city', 'Miasto') }}
                {{ Form::text('city', $property->city, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
        
            {{ Form::hidden('property_id', $property->id) }}
            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::submit('Aktualizuj', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    </div>
</div>
@endsection