@extends('layouts.app')

@section('content')

{!! Html::style('css/property_list.css') !!}

<div class="container">
    <h1 class="text-center">Wybierz Lokalizacje z której interesują Cie pakiety</h1>
    <div id="properties" class="wrapper">
        @foreach ($properties as $property)
            @if ($property->boss_id)
                <div class="text-center box card" style="background-color: lightgreen;">
            @else
                <div class="text-center box card">
            @endif
                <div class="card-body">
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
                    <a href="{{ URL::to('user/property/subscription/list/' . $property->id) }}" class="btn btn-success">
                        Zobacz
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection