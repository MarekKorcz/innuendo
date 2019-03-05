@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::to('property/' . $property_id) }}">
                    Back to Property
                </a>
            </li>
        </ul>
    </nav>

    <h1>Create a Subscription</h1>

    {{ Form::open(['action' => 'SubscriptionController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" class="form-control" type="text" name="name">
        </div>    
        <div class="form-group">
            <label for="description">Description</label>
            <input id="description" class="form-control" type="text" name="description">
        </div>
        <div class="form-group">
            <label for="old_price">Old price</label>
            <input id="old_price" class="form-control" type="text" name="old_price">
        </div>
        <div class="form-group">
            <label for="new_price">New price</label>
            <input id="new_price" class="form-control" type="text" name="new_price">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity per month</label>
            <input id="quantity" class="form-control" type="text" name="quantity">
        </div>
        <div class="form-group">
            <label for="duration">Duration (how many months)</label>
            <input id="duration" class="form-control" type="text" name="duration">
        </div>
        @if ($property_id)
            {{ Form::hidden('property_id', $property_id) }}
        @else
            {{ Form::hidden('property_id', Input::old('property_id')) }}
        @endif
        {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection