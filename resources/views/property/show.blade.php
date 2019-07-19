@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                {!!Form::open(['action' => ['PropertyController@destroy', $property->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                {!!Form::close()!!}
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a class="btn btn-success" href="{{ URL::to('property/' . $property->id . '/edit') }}">
                        Edit
                    </a>
                </li>
            </ul>
        </nav>
        
        <h2 class="text-center">Property values:</h2>
        
        <table class="table table-striped">
            <tr>
                <th>Name:</th>
                <th>Owner:</th>
                <th>Description:</th>
                <th>Street:</th>
                <th>Street number:</th>
                <th>House number:</th>
                <th>City:</th>
                <th>canShow:</th>
            </tr>
            <tr>
                <td>{{ $property->name }}</td>
                @if ($property->boss !== null)
                    <td>{{ $property->boss->name }} {{ $property->boss->surname }}</td>
                @else
                    <td>Public</td>
                @endif
                <td>{!! $property->description !!}</td>
                <td>{{ $property->street }}</td>
                <td>{{ $property->street_number }}</td>
                <td>{{ $property->house_number }}</td>
                <td>{{ $property->city }}</td>
                <td>
                    @if ($property->canShow == 0)
                        <a class="btn btn-success" style="margin: 9px;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                            Show
                        </a>
                    @else
                        <a class="btn btn-success" style="margin: 9px;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                            Don't Show
                        </a>
                    @endif
                </td>
            </tr>
        </table>
        
        <h3 class="text-center">Subscriptions:</h3>
    
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" style="margin: 9px;" href="{{ URL::to('subscription/create/' . $property->id) }}">
                    Create Subscription
                </a>
            </li>
        </ul>

        @if (count($subscriptions) > 0)
            <table class="table table-striped">
                <tr>
                    <th>Name:</th>
                    <th>Description:</th>
                    <th>Old Price:</th>
                    <th>New price:</th>
                    <th>Quantity:</th>
                    <th>Duration:</th>
                </tr>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->name }}</td>
                            <td>{!! $subscription->description !!}</td>
                            <td>{{ $subscription->old_price }}</td>
                            <td>{{ $subscription->new_price }}</td>
                            <td>{{ $subscription->quantity }}</td>
                            <td>{{ $subscription->duration }}</td>
                        </tr>
                    @endforeach
            </table>
        @else
            <h4>There is no subscriptions attached</h4>
        @endif
    </div>
        
    @if ($calendars)
        @foreach ($calendars as $calendar)
            <div class="jumbotron">
                <div style="padding: 5px;">
                    @if ($calendar->isActive)
                        {!!Form::open(['action' => ['CalendarController@deactivate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                            {{ Form::hidden('property_id', $property->id) }}
                            {{ Form::hidden('_method', 'POST') }}
                            {{ Form::submit('Deactivate Calendar', ['class' => 'btn btn-primary']) }}
                        {!!Form::close()!!}
                    @else
                        {!!Form::open(['action' => ['CalendarController@activate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                            {{ Form::hidden('property_id', $property->id) }}
                            {{ Form::hidden('_method', 'POST') }}
                            {{ Form::submit('Activate Calendar', ['class' => 'btn btn-success']) }}
                        {!!Form::close()!!}
                    @endif
                </div>
                <div style="padding: 5px;">
                    {!!Form::open(['action' => ['CalendarController@destroy', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                        {{ Form::hidden('property_id', $property->id) }}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Delete Calendar', ['class' => 'btn btn-danger']) }}
                    {!!Form::close()!!}
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
                            Assign Calendar to Employee
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