@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}
{!! Html::script('js/codes.js') !!}

<div class="container">
    
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
                                {!!Form::open(['action' => ['BossController@destroyCode', $codes[$i]['code_id']], 'method' => 'POST'])!!}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                                {!!Form::close()!!}
                            </div>
                            <div class="text-center">
                                {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
                                    @if ($codes[$i]['properties'])
                                        @foreach ($codes[$i]['properties'] as $property)
                                            <div class="code-items" data-code_id="{{$codes[$i]['code_id']}}" style="padding: 12px 21px 12px 21px;">
                                                <ul class="property">
                                                    @if ($property['chosen_property_id'] != 0)
                                                        <li class="form-control" 
                                                            data-active="true" 
                                                            data-property_id="{{$property['property_id']}}" 
                                                            data-chosen_property_id="{{$property['chosen_property_id']}}" 
                                                            style="background-color: lightskyblue;"
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
                                                <ul class="subscriptions" data-chosen_property_id="{{$property['chosen_property_id']}}">
                                                    @foreach ($property['subscriptions'] as $subscription)
                                                        @if ($subscription['isChosen'])
                                                            <li class="form-control" 
                                                                data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                data-active="true" 
                                                                style="background-color: lightgreen;"
                                                            >
                                                                {{$subscription['subscription_name']}}
                                                                @if ($subscription['isSubscriptionStarted'] !== null)
                                                                    {{$subscription['isSubscriptionStarted']}}
                                                                @endif
                                                            </li>
                                                        @else
                                                            <li class="form-control" 
                                                                data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                data-active="false"
                                                            >
                                                                {{$subscription['subscription_name']}}
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
                                                <a class="btn btn-info copy-button">
                                                    @lang('common.registration_code_copy')
                                                </a>
                                            </p>

                                            <input name="code" type="hidden" value="false">
                                            <input type="submit" value="@lang('common.turn_registration_off')" class="btn btn-warning">
                                        @else
                                            <input name="code" type="hidden" value="true">
                                            <input type="submit" value="@lang('common.turn_registration_on')" class="btn btn-success">
                                        @endif
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endfor

            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <a class="btn btn-success" href="{{ action('BossController@addCode') }}">
                    @lang('common.add_new_code')
                </a>
            </div>
        </div>
    @else
        <div class="text-center" style="padding: 1rem;">
            <h2>@lang('common.register_codes')</h2>
            <h4>@lang('common.register_codes_description')</h4>
            <div style="padding: 1rem;">
                <a class="btn btn-success btn-lg" href="{{ action('BossController@addCode') }}">
                    @lang('common.add_new_code')
                </a>
            </div>
        </div>
    @endif
</div>
@endsection