@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['SubscriptionController@destroy', $subscription->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::to('/property/index') }}">
                    Property Index
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>{{ $subscription->name }}</h2>
        </div>
        <p>{{ $subscription->description }}</p>
        <p>Old price: <strong>{{ $subscription->old_price }}</strong></p>
        <p>New price: <strong>{{ $subscription->new_price }}</strong></p>
        <p>Quantity per month: <strong>{{ $subscription->quantity }}</strong></p>
        <p>How many months since start: <strong>{{ $subscription->duration }}</strong></p>
    </div> 
</div>
@endsection