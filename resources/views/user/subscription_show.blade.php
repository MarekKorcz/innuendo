@extends('layouts.app')

@section('content')

<!--{!! Html::script('js/property_show.js') !!}-->

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{ $subscription->name }}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>Nazwa: <strong>{{$subscription->name}}</strong></p>
                <p>@lang('common.description') : <strong>{{ $subscription->description }}</strong></p>
                <p>Cena:  
                    <strike>{{$subscription->old_price}}</strike>
                    <strong>{{$subscription->new_price}}</strong>
                </p>
                <p>Ilość zabiegów do wykorzystania w miesiącu: <strong>{{$subscription->quantity}}</strong></p>
                <p>Czas trwania pakietu: <strong>{{$subscription->duration}} miesięcy</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    @if ($isPurchasable)
                        <a href="{{ URL::to('user/subscription/purchase/' . $property->id . '/' . $subscription->id)}}" class="btn btn-success btn-lg">
                            Przejdz do widoku realizacji
                        </a>
                    @else                    
                        <a href="{{ URL::to('user/subscription/purchased/show/' . $subscription->purchase_id)}}" class="btn btn-success btn-lg">
                            Przejdz do wykupionej subskrypcji
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection