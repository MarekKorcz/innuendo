@extends('layouts.app')

@section('content')

{!! Html::style('css/subscription_purchased_property_list.css') !!}

<div class="container">    
    <div class="text-center">
        <h1 class="text-center padding">Lista lokalizacji z wykupionymi pakietami</h1>
        <hr>
    </div>
    <div id="properties" class="wrapper cont">
        @foreach ($properties as $property)
            <div class="text-center box">
                <div class="card">
                    <div class="text-center">
                        @svg('brands/dev')
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <strong>
                                {{$property->name}}
                            </strong>
                        </h5>
                        <p class="card-text">
                            {!!$property->description!!}
                            <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}} {{$property->city}}</strong></p>
                        </p>
                        <div class="text-center">
                            <a class="btn btn-success" href="{{ URL::to('user/subscription/list/purchased/' . $property->id) }}">
                                Zobacz
                            </a>                                    
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection