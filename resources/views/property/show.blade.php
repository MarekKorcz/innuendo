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
                <a href="{{ URL::to('/subscription/index') }}" class="btn btn-secondary">
                    View All Subscriptions
                </a>
                <a class="btn btn-success" href="{{ URL::to('property/' . $property->id . '/edit') }}">
                    Edit
                </a>
            </li>
        </ul>
    </nav>

    <h2 style="padding: 20px;">{{ $property->name }}</h2>
    
    <hr>

    <div class="jumbotron">
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::to('/subscription/index/property/' . $property->id) }}">
                    Property's Subscriptions
                </a>
            </li>
        </ul>
        
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Values:</h2>
        </div>
        <table class="table table-striped">
            <tr>
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
            </tr>
            <tr>
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
            </tr>
        </table>
    </div>
        
    @if ($calendars)
        @foreach ($calendars as $calendar)
            <div class="jumbotron">
                <div style="padding: 5px;">
                    @if ($calendar->isActive)
                        {!!Form::open(['action' => ['CalendarController@deactivate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                            {{ Form::hidden('property_id', $property->id) }}
                            {{ Form::hidden('_method', 'POST') }}
                            {{ Form::submit('Deactivate calendar', ['class' => 'btn btn-primary']) }}
                        {!!Form::close()!!}
                    @else
                        {!!Form::open(['action' => ['CalendarController@activate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                            {{ Form::hidden('property_id', $property->id) }}
                            {{ Form::hidden('_method', 'POST') }}
                            {{ Form::submit('Activate calendar', ['class' => 'btn btn-success']) }}
                        {!!Form::close()!!}
                    @endif
                </div>
                <div style="padding: 5px;">
                    {!!Form::open(['action' => ['CalendarController@destroy', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                        {{ Form::hidden('property_id', $property->id) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                    {!!Form::close()!!}
                </div>
                <div style="padding: 5px;">
                    <a class="btn btn-success" href="{{ URL::to('subscription/create/' . $property->id) }}">
                        Create Subscription
                    </a>
                </div>
                
                @if ($calendar->employee_id != null)
                    <div class="text-center" style="margin-bottom: 40px;">
                        <h2 style="margin-bottom: 15px;">
                            Calendar assigned to 
                            <a href="{{ URL::to('employee/' . $employees[$calendar->id]->slug) }}">
                                {{$employees[$calendar->id]->name}}
                            </a>
                        </h2>
                        <h3>Years:</h3>
                    </div>
                    @if (count($years[$calendar->id]) > 0)
                        <div class="list-group">
                            @foreach ($years[$calendar->id] as $year)
                                <a class="list-group-item text-center" href="{{ URL::to('year/show/' . $year->id) }}">
                                    <h4>{{$year->year}}</h4>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="text-center" style="padding-top: 30px;">
                        <a class="btn btn-success" href="{{ action('YearController@create', $calendar->id) }}">
                            Add Year
                        </a>
                    </div>
                @else
                    <h1 class="text-center">New calendar</h1>
                    <div class="text-center" style="padding-top: 30px;">
                        <a class="btn btn-primary" href="{{ action('EmployeeController@assign', $calendar->id) }}">
                            Assign calendar to Employee
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
        <a class="btn btn-success" href="{{ action('CalendarController@create', $property->id) }}">
            Create Calendar
        </a>
    </div>
    
</div>
@endsection