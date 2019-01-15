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

    <h2>Showing year - {{ $year->year }}</h2>
    
    <div class="jumbotron">
        @if (count($months) > 0)
            @foreach ($months as $month)
                <div class = "row justify-content-md-center">
                    <a class="btn btn-lg btn-primary" style="margin-bottom: 10px;" href="{{ URL::to('month/show/' . $month->id) }}">
                        {{$month->month}}
                    </a>
                </div>
            @endforeach
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('MonthController@create', $year->id) }}">
                Add Month
            </a>
        </div>
    </div>
</div>
@endsection