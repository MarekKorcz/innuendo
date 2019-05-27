@extends('layouts.app')

@section('content')

{!! Html::style('css/property_show.css') !!}

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">Grafiki w {{ $property->name }}</h1>
   
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>Opis</h3>
                <p>Nazwa: <strong>{{$property->name}}</strong></p>
                <p>Data powstania: <strong>{{ $propertyCreatedAt }}</strong></p>
                @if ($property->description)
                    <span>Opis: {!! $property->description !!}</span>
                @endif
                <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h2>todo: Place to show something??</h2>
            </div>
        </div>
    </div>
    
    <h2 class="text-center" style="padding: 2rem;">Grafiki naszych pracowników:</h2>
    
    <div class="wrapper">
        @if (count($employees) > 0)
            @foreach ($employees as $employee)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">{{$employee->name}}</h5>
                        <p class="card-text">
                            {!!$employee->description!!}
                        </p>
                        <div class="text-center">
                            <a href="{{ URL::to('employee/' . $employee->slug) }}" class="btn btn-success btn-lg">
                                Zobacz Profil
                            </a>
                            <a href="{{ URL::to('employee/calendar/' . $employee->calendar . '/' . $today['year'] . '/' . $today['month'] . '/' . $today['day'] ) }}" class="btn btn-success btn-lg">
                                Zobacz Grafik
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <h3 class="text-center">Obecnie żaden z naszych pracowników nie jest przypisany do lokalizacji</h3>
        @endif
    </div>
</div>
@endsection