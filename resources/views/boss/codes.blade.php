@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}
{!! Html::script('js/codes.js') !!}

<div class="container">
    
    <div style="padding: 1rem 0 1rem 0">        
        @if (count($codes) > 0)
            <div class="text-center" style="padding-top: 1rem;">
                <h2>@lang('common.register_codes')</h2>
            </div>

            <div class="jumbotron" style="margin-top: 30px;">
                @for ($i = 1; $i <= count($codes); $i++)
                    <div class="row" style="padding: 12px;">
                        <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                            <div class="card">
                                <div class="text-right" style="padding: 6px;">
                                    <a class="btn pallet-2-2 delete" style="color: white;" data-code_id="{{$codes[$i]['code_id']}}">@lang('common.delete')</a>
                                </div>
                                <div class="text-center">
                                    {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
                                        @if ($codes[$i]['properties'])
                                            @foreach ($codes[$i]['properties'] as $property)
                                                <div class="code-items" data-code_id="{{$codes[$i]['code_id']}}" style="padding: 12px 21px 12px 21px;">
                                                    <h4>@lang('common.properties')</h4>
                                                    <ul class="property" style="padding: 0 2rem 0 2rem;">
                                                        @if ($property['chosen_property_id'] != 0)
                                                            <li class="form-control property-highlight" 
                                                                data-active="true" 
                                                                data-property_id="{{$property['property_id']}}" 
                                                                data-chosen_property_id="{{$property['chosen_property_id']}}"
                                                            >
                                                                {{$property['property_name']}}
                                                            </li>
                                                        @else
                                                            <li class="form-control" 
                                                                data-active="false" 
                                                                data-property_id="{{$property['property_id']}}" 
                                                                data-chosen_property_id="{{$property['chosen_property_id']}}"
                                                            >
                                                                {{$property['property_name']}}
                                                            </li>
                                                        @endif
                                                    </ul>     
                                                    <h4>@lang('common.subscriptions')</h4>
                                                    <ul class="subscriptions" data-chosen_property_id="{{$property['chosen_property_id']}}" style="padding: 0 2rem 0 2rem;">
                                                        @foreach ($property['subscriptions'] as $subscription)
                                                            @if ($subscription['isChosen'])
                                                                <li class="form-control subscription-highlight" 
                                                                    data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                    data-active="true"
                                                                >
                                                                    {!! $subscription['subscription_name'] !!}
                                                                    @if ($subscription['isSubscriptionStarted'] !== null)
                                                                        {{$subscription['isSubscriptionStarted']}}
                                                                    @endif
                                                                </li>
                                                            @else
                                                                <li class="form-control" 
                                                                    data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                    data-active="false"
                                                                >
                                                                    {!! $subscription['subscription_name'] !!}
                                                                    @if ($subscription['isSubscriptionStarted'] !== null)
                                                                        {{$subscription['isSubscriptionStarted']}}
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div style="padding: 0 12px 12px 12px;">
                                            <input name="code_id" type="hidden" value="{{$codes[$i]['code_id']}}">

                                            @if ($codes[$i]['code'])
                                                <p>
                                                    @lang('common.registration_code_description')
                                                </p>
                                                <p>
                                                    @lang('common.registration_code') :
                                                    <input class="code-text" name="code-text" type="text" value="{{$codes[$i]['code']}}" style="margin: 0px 12px 0px 12px;">
                                                    <a class="btn pallet-1-3 copy-button" style="color: white;">
                                                        @lang('common.registration_code_copy')
                                                    </a>
                                                </p>

                                                <input name="code" type="hidden" value="false">
                                                <input type="submit" value="@lang('common.turn_registration_off')" class="btn pallet-2-4" style="color: white;">
                                            @else
                                                <input name="code" type="hidden" value="true">
                                                <input type="submit" value="@lang('common.turn_registration_on')" class="btn pallet-1-3" style="color: white;">
                                            @endif
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor

                <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                    <a class="btn pallet-2-1" style="color: white;" href="{{ action('BossController@addCode') }}">
                        @lang('common.add_new_code')
                    </a>
                </div>
            </div>
        @else
            <div class="text-center" style="padding: 1rem;">
                <h2 style="padding-bottom: 1rem;">@lang('common.register_codes')</h2>
                @if ($redirectToSubscriptionPurchaseView)
                    <h3>@lang('common.go_to_subscription_list_description')</h3>
                    <h4>@lang('common.go_to_schedule_description_4')</h4>
                    <div style="padding: 1rem;">
                        <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/boss/subscription/list/0/0') }}">
                            @lang('common.subscriptions_list')
                        </a>
                    </div>
                @else
                    <h4>@lang('common.register_codes_description')</h4>
                    <div style="padding: 1rem;">
                        <a class="btn pallet-2-1 btn-lg" style="color: white;" href="{{ action('BossController@addCode') }}">
                            @lang('common.add_new_code')
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
    
    <div id="addProperty" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.property_on')</h4>
                <button id="addPropertyCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <a id="addPropertyButton" class="btn pallet-1-3" style="color: white;">@lang('common.turn_on')</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="removeProperty" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.property_off')</h4>
                <button id="removePropertyCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <a id="removePropertyButton" class="btn pallet-1-3" style="color: white;">@lang('common.turn_off')</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="addSubscription" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.subscription_on')</h4>
                <button id="addSubscriptionCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <a id="addSubscriptionButton" class="btn pallet-1-3" style="color: white;">@lang('common.turn_on')</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="removeSubscription" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.subscription_off')</h4>
                <button id="removeSubscriptionCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <a id="removeSubscriptionButton" class="btn pallet-1-3" style="color: white;">@lang('common.turn_off')</a>
                </div>
            </div>
        </div>
    </div>
    
    <div id="deleteCode" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.code_delete')</h4>
                <button id="deleteCodeCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn pallet-2-2" style="color: white;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
    
</div>

@endsection