@extends('layouts.app')
@section('content')

{!! Html::style('css/property_subscriptions_purchase.css') !!}

<div class="container">
    <div class="text-center">
        <h1>@lang('common.subscriptions_assigned_to') {{$property->name}}</h1>
    </div>
    <div id="subscriptions" class="wrapper">
        @if (count($subscriptions) > 0)
            @foreach ($subscriptions as $subscription)
                @if ($subscription->isPurchased)
                    <div class="card" style="background-color: lightgreen;">
                @else
                    <div class="card">
                @endif
                    <div class="text-center">
                        <!--todo: co tutaj?-->
                        ZDJĘCIE
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center">{!! $subscription->name !!}</h5>
                        <p class="card-text">
                            {!!$subscription->description!!}
                        </p>
                        @if ($subscription->isPurchased)
                            <a href="{{ URL::to('boss/subscription/list/' . $property->id) . '/' . $subscription->id }}" class="btn btn-success">
                                @lang('common.show')
                            </a>
                        @else
                            <a href="{{ URL::to('boss/subscription/purchase/' . $property->id) . '/' . $subscription->id }}" class="btn btn-success">
                                @lang('common.order')
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <!--todo: co z tym?-->
            <h3>tu musi byc lista z Buttonem do zakupu</h3>
        @endif
    </div>
</div>
@endsection