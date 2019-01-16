@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['MonthController@destroy', $month->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                @if ($year)
                    <a class="btn btn-success" href="{{ URL::to('year/show/' . $year->id) }}">
                        Back to Year
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2>Showing month - {{ $month->month }}</h2>
    
    <div class="jumbotron">
        @if (count($days) > 0)
            <p>Show days</p>
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('DayController@create', $month->id) }}">
                Add Days
            </a>
        </div>
    </div>
</div>
@endsection