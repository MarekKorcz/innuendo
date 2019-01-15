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
        
    @if ($calendar)
        <div class="jumbotron">
            @if (count($years) > 0)
                @foreach ($years as $year)
                    <div class = "row justify-content-md-center">
                        <a class="btn btn-lg btn-primary" style="margin-bottom: 10px;" href="{{ URL::to('year/show/' . $year->id) }}">
                            {{$year->year}}
                        </a>
                    </div>
                @endforeach
            @endif
            <div class="text-center" style="padding-top: 50px;">
                <a class="btn btn-success" href="{{ action('YearController@create', $calendar->id) }}">
                    Add Year
                </a>
            </div>
        </div>
    @endif

</div>
@endsection