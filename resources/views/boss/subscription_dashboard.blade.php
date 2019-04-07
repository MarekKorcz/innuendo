@extends('layouts.app')
@section('content')

{!! Html::script('js/subscription_dashboard.js') !!}
{!! Html::style('css/subscription_dashboard.css') !!}

<div class="container">
    <div class="text-center">
        <h1>Widok Twoich Subskrypcji</h1>
        <h2>Wybierz lokalizacje której subskrypcje chcesz zobaczyć:</h2>
    </div>
    <div id="properties" class="wrapper cont">
        @foreach ($properties as $key => $property)
            @if ($key == 0)
                <div class="text-center highlighted" data-property_id="{{$property->id}}">
            @else
                <div class="text-center box" data-property_id="{{$property->id}}">
            @endif
                    <div class="data">
                        <p><strong>{{$property->name}}</strong></p>
                        {!!$property->description!!}
                        <p>Adres: <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}} {{$property->city}}</strong></p>
                    </div>
                </div>
        @endforeach
    </div>
    <div class="text-center">
        <h2>Subskrypcje należące do wybranej lokalizacji:</h2>
    </div>
    <div id="subscriptions" class="wrapper cont">
        @foreach ($firstPropertySubscriptions as $subscription)
            <div class="box text-center" data-subscription_id="{{$subscription->id}}">
                <div class="data">
                    <p>Nazwa: <strong>{{$subscription->name}}</strong></p>
                    {!!$subscription->description!!}
                    <p>Cena regularna: <strong>{{$subscription->old_price}}</strong></p>
                    <p>Cena z subskrypcją: <strong>{{$subscription->new_price}}</strong></p>
                    <p>Ilość zabiegów w miesiącu: <strong>{{$subscription->quantity}}</strong></p>
                    <p>Czas subskrypcji (w miesiącach): <strong>{{$subscription->duration}}</strong></p>
                </div>
            </div>
        @endforeach
    </div>
    <div id="workers"></div>
</div>
@endsection