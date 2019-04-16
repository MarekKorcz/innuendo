@extends('layouts.app')
@section('content')

{!! Html::style('css/property_subscriptions_purchase.css') !!}

<div class="container">
    <div class="text-center">
        <h1>Subskrypcje przypisane do {{$property->name}}</h1>
    </div>
    <div id="subscriptions" class="wrapper">
        @foreach ($subscriptions as $subscription)
            @if ($subscription->isPurchased)
                <div class="card" style="background-color: lightgreen;">
            @else
                <div class="card">
            @endif
                <div class="text-center">
                    ZDJĘCIE
                </div>
                <div class="card-body">
                    <h5 class="card-title text-center">{{$subscription->name}}</h5>
                    <p class="card-text">
                        {!!$subscription->description!!}
                    </p>
                    @if ($subscription->isPurchased)
                        <a href="{{ URL::to('boss/subscription/list/' . $property->id) . '/' . $subscription->id }}" class="btn btn-success">
                            Zobacz
                        </a>
                    @else
                        <a href="{{ URL::to('boss/subscription/purchase/' . $property->id) . '/' . $subscription->id }}" class="btn btn-success">
                            Zobacz
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection