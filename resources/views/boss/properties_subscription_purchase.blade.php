@extends('layouts.app')
@section('content')

{!! Html::style('css/properties_subscription_purchase.css') !!}

<div class="container">
    <div class="text-center">
        <h1>Wybierz Lokalizację w której chcesz wykupić subskrypcje</h1>
    </div>
    <div id="properties" class="wrapper">
        @foreach ($properties as $property)
            @if ($property->isOwn)
                <div class="card" style="background-color: lightgreen;">
            @else
                <div class="card">
            @endif
                <div class="text-center">
                    ZDJĘCIE
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">{{$property->name}}</h5>
                    <p class="card-text">
                        {!!$property->description!!}
                    </p>
                    @if ($property->isOwn)
                        <a href="{{ URL::to('boss/property/subscriptions/purchase/' . $property->id) }}" class="btn btn-success">
                            Zobacz
                        </a>
                    @else
                        <a href="{{ URL::to('user/property/subscription/list/' . $property->id) }}" class="btn btn-success">
                            Zobacz
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection