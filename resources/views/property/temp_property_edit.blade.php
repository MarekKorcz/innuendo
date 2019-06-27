@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <h1>Edit Temporary Property</h1>

        {{ Form::open(['action' => ['PropertyController@tempPropertyUpdate', $tempProperty->id], 'method' => 'POST']) }}

            <div class="form-group">
                {{ Form::label('name', 'Name') }}
                {{ Form::text('name', $tempProperty->name, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('street', 'Street') }}
                {{ Form::text('street', $tempProperty->street, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('street_number', 'Street number') }}
                {{ Form::text('street_number', $tempProperty->street_number, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('house_number', 'House number') }}
                {{ Form::text('house_number', $tempProperty->house_number, array('class' => 'form-control')) }}
            </div>
            <div class="form-group">
                {{ Form::label('city', 'City') }}
                {{ Form::text('city', $tempProperty->city, array('class' => 'form-control')) }}
            </div>

            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
    </div>
</div>
@endsection