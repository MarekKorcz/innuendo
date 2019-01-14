@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/property/index') }}" class="btn btn-primary">
                    View All Properties
                </a>
            </li>
        </ul>
    </nav>

    <h2>Showing - {{ $property->name }}</h2>

    <div class="jumbotron">
        <p>
            <strong>Id:</strong> {{ $property->id }}<br>
            <strong>Name:</strong> {{ $property->name }}<br>
            <strong>Slug:</strong> {{ $property->slug }}<br>
            <strong>Description:</strong> {{ $property->description }}<br>
            <strong>Phone number:</strong> {{ $property->phone_number }}<br>
            <strong>Street:</strong> {{ $property->street_number }}<br>
            <strong>House number:</strong> {{ $property->house_number }}<br>
            <strong>City:</strong> {{ $property->city }}<br>
            <strong>Created at:</strong> {{ $property->created_at }}<br>
            <strong>Updated at:</strong> {{ $property->updated_at }}<br>
            <strong>Deleted at:</strong> {{ $property->deleted_at }}<br>
            <strong>Owner id:</strong> {{ $property->user_id }}
        </p>
    </div>

</div>
@endsection