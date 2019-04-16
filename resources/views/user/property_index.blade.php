@extends('layouts.app')

@section('content')

{!! Html::style('css/property_index.css') !!}

<div class="container">
    <h1 class="text-center padding-top">Wybierz Lokalizacje z której interesują Cie grafiki</h1>
    
    <div class="wrapper">
        @foreach ($properties as $property)
            @if ($property->isPurchased)
                <div class="card" style="background-color: lightgreen;">
            @else
                <div class="card">
            @endif
                <div class="card-body">
                    <h5 class="card-title text-center">{{$property->name}}</h5>
                    <p class="card-text">
                        {!!$property->description!!}
                    </p>
                    <a href="{{ URL::to('user/property/' . $property->id) }}" class="btn btn-success">
                        Zobacz
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection