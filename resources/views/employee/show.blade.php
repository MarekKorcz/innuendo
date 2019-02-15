@extends('layouts.app')
@section('content')
<div class="container">

    <h2 style="padding: 20px;">{{ $employee->name }}</h2>
    
    <hr>

    <div class="jumbotron">
        <div style="float: left; width: 50%; height: 300px;">
            <h3 style="padding: 9px;">Opis</h3>
            <p>Imię: <strong>{{$employee->name}}</strong></p>
            <p>Adres e-mail: <strong>{{$employee->email}}</strong></p>
            <p>Pracuje od: <strong>{{ \Carbon\Carbon::parse($employee->created_ad)->format('d.m.Y')}}</strong></p>
        </div>
        <div style="float: left; width: 50%; height: 300px;">
            <h3 style="padding: 9px;">Grafik w:</h3>
            @if ($calendars && count($calendars) == count($properties))
                @for ($i = 0; $i < count($calendars); $i++)
                    <a class="btn btn-success" href="{{ URL::to('employee/calendar/' . $calendars[$i]->id . '/0/0/0') }}">
                        {{ $properties[$i]->name }}
                    </a>
                @endfor
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
@endsection