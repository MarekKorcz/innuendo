@extends('layouts.app')

@section('content')

{!! Html::style('css/subscription_purchased_list.css') !!}

<div class="container">

    <h1 class="text-center padding">Lista wykupionych pakietów w <strong>{{$property->name}}</strong></h2>
    
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
                        <a class="btn btn-success" href="{{ URL::to('user/subscription/purchased/show/' . $subscription->purchase_id) }}">
                            @lang('common.show')
                        </a>                                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection