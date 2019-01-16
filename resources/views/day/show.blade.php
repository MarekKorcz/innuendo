@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                @if ($month)
                    <a class="btn btn-success" href="{{ URL::to('month/show/' . $month->id) }}">
                        Back to Month
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2 style="padding: 20px;">Day {{ $day->day_number }}</h2>
    
    <div class="jumbotron">
        @if (count($timeIntervals) > 0)
            
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('TimeIntervalController@create', $day->id) }}">
                Add Time Interval
            </a>
        </div>
    </div>
</div>
@endsection