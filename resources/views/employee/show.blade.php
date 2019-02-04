@extends('layouts.app')
@section('content')
<div class="container">

    <h2 style="padding: 20px;">{{ $employee->name }}</h2>
    
    <hr>

    <div class="jumbotron">
        <div style="float: left; width: 50%; height: 300px;">
            {{$employee->name}}
        </div>
        <div style="float: left; width: 50%; height: 300px;">
            @if ($calendars && count($calendars) == count($properties))
                @for ($i = 0; $i < count($calendars); $i++)
                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0') }}">
                        Calendar in {{ $properties[$i]->name }}
                    </a>
                @endfor
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
@endsection