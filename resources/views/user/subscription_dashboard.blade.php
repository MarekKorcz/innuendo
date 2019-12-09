@extends('layouts.app')
@section('content')

{!! Html::script('js/user_subscription_dashboard.js') !!}
{!! Html::style('css/user_subscription_dashboard.css') !!}

<div class="container">
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div style="padding: 2rem 0 2rem 0">
                <div class="text-center">
                    <h1>@lang('common.subscriptions_view')</h1>
                    <h2>@lang('common.pick_subscription_from_property'):</h2>
                </div>
                <div id="properties" class="wrapper cont">
                    @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                        @if ($propertyWithSubscriptions['property']->isChecked)
                            <div class="text-center highlighted" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
                        @else
                            <div class="text-center box" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
                        @endif
                                <div class="data">
                                    <p>
                                        <strong>
                                            {!! $propertyWithSubscriptions['property']->name !!}
                                        </strong>
                                    </p>
                                    @if ($propertyWithSubscriptions['property']->description)
                                        {!!$propertyWithSubscriptions['property']->description!!}
                                    @endif
                                    <p>
                                        @lang('common.address'): 
                                        <strong>
                                            {{$propertyWithSubscriptions['property']->street}} 
                                            {{$propertyWithSubscriptions['property']->street_number}} / 
                                            {{$propertyWithSubscriptions['property']->house_number}} 
                                            {{$propertyWithSubscriptions['property']->city}}
                                        </strong>
                                    </p>
                                </div>
                                @if ($substart !== null)
                                    <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('user/property/' . $propertyWithSubscriptions['property']->id) }}">
                                        @lang('common.schedules')
                                    </a>
                                @endif
                            </div>
                    @endforeach
                </div>

                <div class="text-center">
                    <h2>@lang('common.available_subscriptions'):</h2>
                </div>
                <div id="subscriptions" class="wrapper cont">
                    @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                        @if ($propertyWithSubscriptions['property']->isChecked == true)
                            @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                                @if ($subscription->isChecked)
                                    <div class="text-center highlighted" data-subscription_id="{{$subscription->id}}">
                                @else
                                    <div class="text-center box" data-subscription_id="{{$subscription->id}}">
                                @endif
                                    <div class="data">
                                        <p>
                                            @lang('common.label'): 
                                            <strong>
                                                {!!$subscription->name!!}
                                            </strong>
                                        </p>
                                        <p>
                                            @lang('common.description'):
                                            <strong>
                                                {!!$subscription->description!!}
                                            </strong>
                                        </p>
                                        <p>
                                            @lang('common.regular_price'): 
                                            <strong>
                                                {{$subscription->old_price}} zł @lang('common.per_person')
                                            </strong>
                                        </p>
                                        <p>
                                            @lang('common.price_with_subscription'): 
                                            <strong>
                                                {{$subscription->new_price}} zł @lang('common.per_person')
                                            </strong>
                                        </p>
                                        <p>
                                            @lang('common.number_of_massages_to_use_per_month'): 
                                            <strong>
                                                {{$subscription->quantity}}
                                            </strong>
                                        </p>
                                        <p>
                                            @lang('common.subscription_duration'): 
                                            <strong>
                                                {{$subscription->duration}}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>

                @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                    @if ($propertyWithSubscriptions['property']->isChecked == true)
                        @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                            @if ($subscription->isChecked && count($subscription->purchases) > 0)
                                <div id="substarts-header" class="text-center">
                                    <h2>
                                        @lang('common.subscription_duration_period'):
                                    </h2>
                                </div>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                <div id="substarts" class="wrapper cont">
                    @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                        @if ($propertyWithSubscriptions['property']->isChecked == true)
                            @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                                @if ($subscription->isChecked && count($subscription->purchases) > 0)
                                    @foreach ($subscription->purchases as $purchase)
                                        @if ($purchase->substart !== null)
                                            @if ($purchase->substart->isCurrent)
                                                <div class="substart text-center highlighted" data-substart_id="{{$purchase->substart->id}}">
                                            @else
                                                <div class="substart text-center box" data-substart_id="{{$purchase->substart->id}}">
                                            @endif
                                                <div class="data">
                                                    <p>
                                                        @lang('common.from'): 
                                                        <strong>
                                                            {{$purchase->substart->start_date->format('Y-m-d')}}
                                                        </strong> 
                                                        @lang('common.to'): 
                                                        <strong>
                                                            {{$purchase->substart->end_date->format('Y-m-d')}}
                                                        </strong>
                                                    </p>
                                                    @if ($purchase->substart->isCurrent)
                                                        @if ($purchase->substart->isActive == 1)
                                                            <p>@lang('common.activated')</p>
                                                        @elseif ($purchase->substart->isActive == 0)
                                                            <p>@lang('common.not_activated')</p>
                                                        @endif
                                                    @else
                                                        <p>@lang('common.subscription_duration_has_come_to_an_end')</p>
                                                    @endif
                                                </div>
                                                <a class="btn pallet-1-3" style="color: white;" target="_blanc" href="{{ URL::to('/user/subscription/purchased/show/' . $purchase->id) }}">
                                                    @lang('common.appointments')
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection