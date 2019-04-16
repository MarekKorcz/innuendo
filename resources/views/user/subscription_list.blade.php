@extends('layouts.app')

@section('content')

{!! Html::style('css/subscription_list.css') !!}

<div class="container">

    <h1 class="text-center padding">Lista dostępnych pakietów</h2>
    
    <hr>
    
    <div class="wrapper">
        @foreach ($subscriptions as $subscription)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">{{$subscription->name}}</h5>
                    <p class="card-text">
                        {!!$subscription->description!!}
                    </p>
                    <div class="text-center">
                        @if ($subscription->purchase_id)
                            <a class="btn btn-success" href="{{ URL::to('user/subscription/purchased/show/' . $subscription->purchase_id) }}">
                                Zobacz wykupione
                            </a>
                        @else
                            <a class="btn btn-success" href="{{ URL::to('user/subscription/show/' . $property->id . '/' . $subscription->id) }}">
                                Wykup
                            </a>  
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection