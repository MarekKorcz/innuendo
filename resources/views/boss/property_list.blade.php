@extends('layouts.app')

@section('content')

{!! Html::style('css/property_list.css') !!}

<div class="container">
    <h1 class="text-center padding-top">Twoje Lokalizacje</h1>
    
    
    <div class="wrapper">
        @foreach ($properties as $property)
            <div class="card">
                <div class="text-center box">
                    <p>
                        <strong>
                            {{$property->name}}
                        </strong>
                    </p>
                    {!!$property->description!!}
                    <p>Adres: 
                        <strong>
                            {{$property->street}} 
                            {{$property->street_number}} / 
                            {{$property->house_number}} 
                            {{$property->city}}
                        </strong>
                    </p>
                    <div>
                        <h1>Todo: link do widoku zmiany opisu property bossa</h1>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection