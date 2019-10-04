@extends('layouts.app')

@section('content')

<!--{!! Html::script('js/property_show.js') !!}-->

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{ $subscription->name }}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>@lang('common.label') : <strong>{!! $subscription->name !!}</strong></p>
                <p>@lang('common.description') : <strong>{!! $subscription->description !!}</strong></p>
                <p>
                    @lang('common.price') :  
                    <strike>{{$subscription->old_price}}</strike>
                    <strong>{{$subscription->new_price}}</strong>
                </p>
                <p>@lang('common.number_of_massages_to_use_per_month') : <strong>{{$subscription->quantity}}</strong></p>
                <p>@lang('common.subscription_duration') : <strong>{{$subscription->duration}} @lang('common.subscription_duration')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    @if ($isPurchasable)
                        <a href="{{ URL::to('user/subscription/purchase/' . $property->id . '/' . $subscription->id)}}" class="btn btn-success btn-lg">
                            @lang('common.purchase_view')
                        </a>
                    @else                    
                        <a href="{{ URL::to('user/subscription/purchased/show/' . $subscription->purchase_id)}}" class="btn btn-success btn-lg">
                            @lang('common.purchased_view')
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection