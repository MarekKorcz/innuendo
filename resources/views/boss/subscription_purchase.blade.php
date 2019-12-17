@extends('layouts.app')

@section('content')

{!! Html::script('js/subscription_purchase.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding: 1rem 0 1rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/boss/subscription/list/' . $property->id . '/' . $subscription->id) }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.go_back')
            </a> 
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>

    <div class="text-center" style="padding: 2rem;">
        <h2>{!! $subscription->name !!}</h2>
    </div>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron">
                <div class="text-center">
                    <p>@lang('common.label'): <strong>{!! $subscription->name !!}</strong></p>
                    <p>@lang('common.description'): <strong>{!! $subscription->description !!}</strong></p>
                    <p>@lang('common.length_of_the_massage'): <strong>{!! $subscription->items->first()->minutes !!} @lang('common.minutes')</strong></p>
                    <p>
                        @lang('common.price'):
                        <strike>{{$subscription->old_price}} zł</strike>
                        <strong>{{$subscription->new_price}} zł @lang('common.per_person')</strong>
                    </p>
                    <p>@lang('common.number_of_massages_to_use_per_month'): <strong>{{$subscription->quantity}}</strong></p>
                    <p>@lang('common.subscription_duration'): <strong>{{$subscription->duration}}</strong></p>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="text-center">
                            {{ Form::open(['id' => 'purchaseForm', 'action' => 'BossController@subscriptionPurchased', 'method' => 'POST']) }}

                                <!--todo: jak tu zrobić tłumaczenie?????-->
                                {!! Html::decode(Form::label('terms','
                                    <span style="font-size: 18px;">
                                        Oświadczam, że zapoznałem/am się z 
                                        <a href="https://masazplusdlafirm.pl/regulations" target="_blank">
                                            Regulaminem
                                        </a>
                                        oraz
                                        <a href="https://masazplusdlafirm.pl/private-policy" target="_blank">
                                            Polityką prywatności
                                        </a>
                                        serwisu masazplusdlafirm.pl i akceptuję wszystkie zawarte w nich warunki:
                                    </span>
                                ')) !!}

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-5"></div>
                                        <div class="col-2" style="position: relative;">
                                            <div>
                                                {{ Form::checkbox('terms', null, null, array('class' => 'form-control', 'style' => 'width: 36px; margin-left: auto; margin-right: auto;')) }}
                                            </div>
                                        </div>
                                        <div class="col-5"></div>
                                    </div>
                                </div>

                                <div class="warning" style="padding-bottom: 6px;"></div>

                                {{ Form::hidden('subscription_id', $subscription->id) }}
                                {{ Form::hidden('property_id', $property->id) }}

                                <input type="submit" value="@lang('common.activate')" class="btn pallet-1-3 btn-lg" style="color: white;">

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection