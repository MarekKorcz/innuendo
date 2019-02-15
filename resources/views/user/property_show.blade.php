@extends('layouts.app')
@section('content')
<div class="container">

    <h2 style="padding: 20px;">{{ $property->name }}</h2>
    
    <hr>

    <div class="jumbotron">
        <div style="float: left; width: 50%; height: 300px;">
            <h3 style="padding: 9px;">Opis</h3>
            <p>Nazwa: <strong>{{$property->name}}</strong></p>
            <p>Data powstania: <strong>{{ \Carbon\Carbon::parse($property->created_ad)->format('d.m.Y')}}</strong></p>
            <p>{!! $property->description !!}</p>
            <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
        </div>
        <div style="float: left; width: 50%; height: 300px;">
            <h3 style="padding: 9px;">Pracownicy: </h3>
            @if ($employees)
                @for ($i = 0; $i < count($employees); $i++)
                    <a class="btn btn-success" href="{{ URL::to('employee/' . $employees[$i]->slug) }}">
                        {{ $employees[$i]->name }}
                    </a>
                @endfor
            @endif
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
@endsection