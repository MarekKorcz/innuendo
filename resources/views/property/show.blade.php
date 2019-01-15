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

    <h2 style="padding: 20px;">Showing - {{ $property->name }}</h2>
    
    <hr>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Values:</h2>
        </div>
        <table class="table table-striped">
            <tr>
                <th>Id:</th>
                <th>Name:</th>
                <th>Slug:</th>
                <th>Description:</th>
                <th>Phone number:</th>
                <th>Street:</th>
                <th>Street number:</th>
                <th>House number:</th>
                <th>City:</th>
                <th>Created at:</th>
                <th>Updated at:</th>
                <th>Owner id:</th>
            </tr>
            <tr>
                <td>{{ $property->id }}</td>
                <td>{{ $property->name }}</td>
                <td>{{ $property->slug }}</td>
                <td>{!! $property->description !!}</td>
                <td>{{ $property->phone_number }}</td>
                <td>{{ $property->street }}</td>
                <td>{{ $property->street_number }}</td>
                <td>{{ $property->house_number }}</td>
                <td>{{ $property->city }}</td>
                <td>{{ $property->created_at }}</td>
                <td>{{ $property->updated_at }}</td>
                <td>{{ $property->user_id }}</td>
            </tr>
        </table>
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
            <div class="text-center" style="margin-bottom: 40px;">
                <h2>Years:</h2>
            </div>
            @if (count($years) > 0)
                <div class="list-group">
                    @foreach ($years as $year)
                        <a class="list-group-item text-center" href="{{ URL::to('year/show/' . $year->id) }}">
                            {{$year->year}}
                        </a>
                    @endforeach
                </div>
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