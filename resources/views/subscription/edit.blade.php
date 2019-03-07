@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/subscription/index') }}" class="btn btn-primary">
                    View All Subscriptions
                </a>
            </li>
        </ul>
    </nav>

    <h1>Edit the Subscription</h1>

    {{ Form::open(['action' => ['SubscriptionController@update', $subscription->id], 'method' => 'POST']) }}

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ $subscription->name }}">
        </div>    
        <div class="form-group">
            <label for="description">Description</label>
            <input id="description" class="form-control" type="text" name="description" value="{{ $subscription->description }}">
        </div>
        <div class="form-group">
            <label for="old_price">Old price</label>
            <input id="old_price" class="form-control" type="text" name="old_price" value="{{ $subscription->old_price }}">
        </div>
        <div class="form-group">
            <label for="new_price">New price</label>
            <input id="new_price" class="form-control" type="text" name="new_price" value="{{ $subscription->new_price }}">
        </div>
        <div class="form-group">
            <label for="quantity">Quantity per month</label>
            <input id="quantity" class="form-control" type="text" name="quantity" value="{{ $subscription->quantity }}">
        </div>
        <div class="form-group">
            <label for="duration">Duration (how many months)</label>
            <input id="duration" class="form-control" type="text" name="duration" value="{{ $subscription->duration }}">
        </div>
    
        {{ Form::hidden('subscription_id', $subscription->id) }}
    
        {{ Form::hidden('_method', 'PUT') }}
        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection