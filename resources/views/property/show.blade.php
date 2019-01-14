@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['PropertyController@destroy', $property->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                    View All Properties
                </a>
                <a class="btn btn-success" href="{{ URL::to('property/' . $property->id . '/edit') }}">
                    Edit
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
            <strong>Description:</strong> {!! $property->description !!}<br>
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
    
    @if (!$calendar)
        <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
            <a class="btn btn-success" href="{{ action('CalendarController@create', $property->id) }}">
                Create Calendar
            </a>
        </div>
    @endif
        
    <div class="jumbotron">
        @if ($calendar)
            <p>Show calendar years</p>
        @else
            <p class="text-center">There is no calendars yet!</p> 
        @endif
    </div>

</div>
@endsection