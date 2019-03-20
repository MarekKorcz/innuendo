@extends('layouts.app')
@section('content')

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{ $property->name }}</h1>
    
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>Opis</h3>
                <p>Nazwa: <strong>{{$property->name}}</strong></p>
                <p>Data powstania: <strong>{{ $propertyCreatedAt }}</strong></p>
                <span>Opis: {!! $property->description !!}</span>
                <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <img class="img-fluid" src="/img/column2.jpg">
            </div>
        </div>
    </div>
    
    <h2 class="text-center" style="padding: 2rem;">Pracownicy:</h2>
    
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
    
    <br>
</div>
@endsection