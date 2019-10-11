@extends('layouts.app')
@section('content')

{!! Html::script('js/subscription_dashboard.js') !!}
{!! Html::style('css/subscription_dashboard.css') !!}

<div class="container">
    
    <div style="padding: 2rem 0 2rem 0">
        <div class="text-center">
            <h1>@lang('common.subscriptions_view')</h1>
            <h2>@lang('common.pick_subscription_from_property') :</h2>
        </div>
        <div id="properties" class="wrapper cont">
            @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                @if ($propertyWithSubscriptions['property']->isChecked)
                    <div class="text-center highlighted" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
                @else
                    <div class="text-center box" data-property_id="{{$propertyWithSubscriptions['property']->id}}">
                @endif
                        <div class="data">
                            <p><strong>{{$propertyWithSubscriptions['property']->name}}</strong></p>
                            @if ($propertyWithSubscriptions['property']->description)
                                {!!$propertyWithSubscriptions['property']->description!!}
                            @endif
                            <p>
                                @lang('common.address') : 
                                <strong>
                                    {{$propertyWithSubscriptions['property']->street}} 
                                    {{$propertyWithSubscriptions['property']->street_number}} / 
                                    {{$propertyWithSubscriptions['property']->house_number}} 
                                    {{$propertyWithSubscriptions['property']->city}}
                                </strong>
                            </p>
                        </div>
                        <a class="btn btn-primary" href="{{ URL::to('boss/property/' . $propertyWithSubscriptions['property']->id . '/edit') }}">
                            @lang('common.edit')
                        </a>
                        @if ($substart !== null)
                            <a class="btn btn-primary" href="{{ URL::to('user/property/' . $propertyWithSubscriptions['property']->id) }}">
                                @lang('common.schedules')
                            </a>
                        @endif
                    </div>
            @endforeach
        </div>

        <div class="text-center">
            <h2>@lang('common.available_subscriptions') :</h2>
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
                                <p>@lang('common.label') : <strong>{!! $subscription->name !!}</strong></p>
                                <p>
                                    @lang('common.description') :
                                    <strong>
                                        {!!$subscription->description!!}
                                    </strong>
                                </p>
                                <p>@lang('common.regular_price') : <strong>{{$subscription->old_price}} zł @lang('common.per_person')</strong></p>
                                <p>@lang('common.price_with_subscription') : <strong>{{$subscription->new_price}} zł @lang('common.per_person')</strong></p>
                                <p>
                                    @lang('common.number_of_massages_to_use_per_month') : 
                                    <strong>
                                        {{$subscription->quantity}}
                                    </strong>
                                </p>
                                <p>
                                    @lang('common.subscription_duration') : 
                                    <strong>
                                        {{$subscription->duration}}
                                    </strong>
                                </p>
                            </div>
                        
                            @if (count($subscription->propertyPurchases) == 0)
                                <a class="btn btn-primary" href="{{ URL::to('/boss/subscription/purchase/' . $propertyWithSubscriptions['property']->id . '/' . $subscription->id) }}">
                                    @lang('common.purchase_subscription')
                                </a>
                            @endif
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>

        @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
            @if ($propertyWithSubscriptions['property']->isChecked == true)
                @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                    @if ($subscription->isChecked && count($subscription->propertyPurchases) > 0)
                        <div id="substarts-header" class="text-center">
                            <h2>
                                @lang('common.subscription_duration_period') :
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
                        @if ($subscription->isChecked && count($subscription->propertyPurchases) > 0)
                            @foreach ($subscription->propertyPurchases as $purchase)
                                @if ($purchase->substart !== null)
                                    @if ($purchase->substart->isCurrent)
                                        <div class="substart text-center highlighted" data-substart_id="{{$purchase->substart->id}}">
                                    @else
                                        <div class="substart text-center box" data-substart_id="{{$purchase->substart->id}}">
                                    @endif
                                        <div class="data">
                                            <p>
                                                @lang('common.from') : <strong>{{$purchase->substart->start_date->format('Y-m-d')}}</strong> 
                                                @lang('common.to') : <strong>{{$purchase->substart->end_date->format('Y-m-d')}}</strong>
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
                                        @if ($purchase->substart->isActive == 1)
                                            <a class="btn btn-primary" href="{{ URL::to('/boss/subscription/invoices/' . $purchase->substart->id) }}">
                                                @lang('common.invoices')
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        <div id="workers">
            @foreach ($propertiesWithSubscriptions as $propertyWithSubscriptions)
                @if ($propertyWithSubscriptions['property']->isChecked == true)
                    @foreach ($propertyWithSubscriptions['subscriptions'] as $subscription)
                        @if ($subscription->isChecked && count($subscription->propertyPurchases) > 0)
                            @foreach ($subscription->propertyPurchases as $purchase)
                                @if ($purchase->substart !== null)
                                    @if ($purchase->substart->isCurrent)
                                        @if (count($purchase->substart->workers) > 0)
                                            <div class="text-center">
                                                <div id="button-space" style="padding: 1rem;">
                                                    <h2>@lang('common.people_assigned_to_subscription') :</h2>
                                                    <a class="btn btn-primary" href="{{ URL::to('boss/subscription/workers/edit/' . $substart->id . '/0') }}">
                                                        @lang('common.edit')
                                                    </a>
                                                    <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $substart->id . '/0') }}">
                                                        @lang('common.all_massages')
                                                    </a>
                                                </div>
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                        <tr>                
                                                            <td>@lang('common.name')</td>
                                                            <td>@lang('common.surname')</td>
                                                            <td>@lang('common.email_address')</td>
                                                            <td>@lang('common.phone_number')</td>
                                                            <td>@lang('common.appointments')</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="workersTable">
                                                    @foreach ($purchase->substart->workers as $worker)
                                                        <tr>
                                                            <td>{{$worker['name']}}</td>
                                                            <td>{{$worker['surname']}}</td>
                                                            <td>{{$worker['email']}}</td>
                                                            <td>{{$worker['phone_number']}}</td>
                                                            <td>
                                                                <a class="btn btn-primary" href="{{ URL::to('boss/worker/appointment/list/' . $substart->id . '/' . $worker['id']) }}">
                                                                    @lang('common.show')
                                                                </a>
                                                            </td>
                                                        </tr>                 
                                                    @endforeach
                                                    </tbody> 
                                                </table>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection