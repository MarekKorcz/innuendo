@extends('layouts.app')
@section('content')

{!! Html::script('js/backend_subscription_show.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                {!!Form::open(['action' => ['SubscriptionController@destroy', $subscription->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                {!!Form::close()!!}
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a class="btn btn-primary" href="{{ URL::to('/subscription/' . $subscription->id . '/edit') }}">
                        @lang('common.edit')
                    </a>
                </li>
            </ul>
        </nav>
        
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>{{ $subscription->name }}</h2>
        </div>
        <div class="text-center">
            <h3>{!! $subscription->description !!}</h3>
        </div>
        <p>@lang('common.old_price') : <strong>{{ $subscription->old_price }}</strong></p>
        <p>@lang('common.new_price') : <strong>{{ $subscription->new_price }}</strong></p>
        <p>@lang('common.quantity_per_month') : <strong>{{ $subscription->quantity }}</strong></p>
        <p>@lang('common.how_many_months_since_start') : <strong>{{ $subscription->duration }}</strong></p>
        <div class="form-group">
            @if (count($properties) > 0)
                <h3 class="text-center">@lang('common.subscriptions_available_for_purchase') :</h3>
                <ul id="properties" data-subscription_id="{{ $subscription->id }}">
                    @foreach($properties as $property)
                        @if($property['active'])
                            <li class="form-control" value="{{ $property['id'] }}" data-active="true" style="background-color: lightgreen;">{{ $property['name'] }}</li>
                        @else
                            <li class="form-control" value="{{ $property['id'] }}">{{ $property['name'] }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
            @if (count($tempProperties) > 0)
                <h3 class="text-center">@lang('common.subscriptions_available_for_purchase_in_temp_properties') :</h3>
                <ul id="tempProperties" data-subscription_id="{{ $subscription->id }}">
                    @foreach($tempProperties as $tempProperty)
                        @if($tempProperty['active'])
                            <li class="form-control" value="{{ $tempProperty['id'] }}" data-active="true" style="background-color: lightgreen;">{{ $tempProperty['name'] }}</li>
                        @else
                            <li class="form-control" value="{{ $tempProperty['id'] }}">{{ $tempProperty['name'] }}</li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
        <h3 class="text-center">@lang('common.items_in_subscription') :</h3>
        <div class="form-group">
            <ul id="items" data-subscription_id="{{ $subscription->id }}">
                @foreach($items as $item)
                    @if($item['active'])
                        <li class="form-control" value="{{ $item['id'] }}" data-active="true" style="background-color: lightgreen;">{{ $item['name'] }}</li>
                    @else
                        <li class="form-control" value="{{ $item['id'] }}">{{ $item['name'] }}</li>
                    @endif
                @endforeach
            </ul>
        </div> 
    </div> 
</div>
@endsection