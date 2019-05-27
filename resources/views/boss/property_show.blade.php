@extends('layouts.app')
@section('content')

<div class="container">
    
    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li>
                <a class="btn btn-success" href="{{ URL::to('boss/property/' . $property->id . '/edit') }}">
                    Edytuj lokalizacje
                </a>
            </li>
        </ul>
    </nav>

    <h1 class="text-center" style="padding: 2rem;">{{ $property->name }}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>Opis</h3>
                <p>Nazwa: <strong>{{$property->name}}</strong></p>
                <p>Data powstania: <strong>{{ $propertyCreatedAt }}</strong></p>
                @if ($property->description)
                    <span>Opis: {!!$property->description!!}</span>
                @endif
                <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
                @if ($property->city)
                    <p>Miasto: <strong>{{$property->city}}</strong></p>
                @endif
                @if ($property->post_code)
                    <p>Kod pocztowy: <strong>{{$property->post_code}}</strong></p>
                @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <img class="img-fluid" src="/img/column2.jpg">
            </div>
        </div>
    </div>
    
    <h2 class="text-center" style="padding: 2rem;">Twoi pracownicy:</h2>
    
    @if ($workers !== null)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>Imię</td>
                    <td>Email</td>
                    <td>Telefon</td>
                </tr>
            </thead>
            <tbody>
                @foreach($workers as $worker)
                    <tr>
                        <td>{{$worker->name}} {{$worker->surname}}</td>
                        <td>{{$worker->email}}</td>
                        <td>{{$worker->phone_number}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h3 class="text-center">Obecnie żaden z Twoich pracowników nie jest przypisany do tej lokalizacji</h3>      
    @endif
    
    <br>
</div>
@endsection