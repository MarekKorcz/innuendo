@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['YearController@destroy', $year->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                @if ($property_id != 0)
                    <a class="btn btn-success" href="{{ URL::to('property/' . $property_id) }}">
                        Back to Property
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                        View All Properties
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2 style="padding: 20px;">{{ $year->year }}</h2>
    
    <hr>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Values:</h2>
        </div>
        <table class="table table-striped">
            <tr>
                <th>Id:</th>
                <th>Year:</th>
                <th>Created at:</th>
                <th>Updated at:</th>
                <th>Property id:</th>
            </tr>
            <tr>
                <td>{{ $year->id }}</td>
                <td>{{ $year->year }}</td>
                <td>{{ $year->created_at }}</td>
                <td>{{ $year->updated_at }}</td>
                <td>{{ $property_id }}</td>
            </tr>
        </table>
    </div>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Months:</h2>
        </div>
        @if (count($months) > 0)
            <div class="list-group">
                @foreach ($months as $month)
                    <a class="list-group-item text-center" href="{{ URL::to('month/show/' . $month->id) }}">
                        <h4>{{$month->month}}</h4>
                    </a>
                @endforeach
            </div>
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('MonthController@create', $year->id) }}">
                Add Month
            </a>
        </div>
    </div>
</div>
@endsection