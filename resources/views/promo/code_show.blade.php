@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}
{!! Html::style('css/graphic_request.css') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        
        <div class="text-center" style="margin-bottom: 18px;">
            <h2>
                @lang('common.promo_code_header') :
                "
                @if (Session('locale') == "en")
                    {{ $promoCode->promo->title_en }}
                @else
                    {{ $promoCode->promo->title }}
                @endif
                "
            </h2>
        </div>
        <div>
            <p>@lang('common.code') : <strong>{{ $promoCode->code }}</strong></p>
            <p>@lang('common.is_active') : 
                <strong>
                    @if ($promoCode->isActive == 1)
                        @lang('common.yes')
                    @else
                        @lang('common.no')
                    @endif
                </strong>
            </p>
            @if ($promoCode->isActive == 1)
                <p>
                    @lang('common.boss') :
                    <strong>
                        <a href="{{ URL::to('/admin/boss/show/' . $promoCode->boss->id) }}">
                            {{ $promoCode->boss->name }} {{ $promoCode->boss->surname }}
                        </a>
                    </strong>
                </p>
                <p>@lang('common.activation_date') : <strong>{{ $promoCode->activation_date }}</strong></p>
            @endif
        </div>
        
        @if (count($promoCode->subscriptions) > 0)
            <h3 class="text-center">@lang('common.subscriptions_list') :</h3>
            <div class="form-group">
                <ul id="subscriptions" style="padding: 9px;">
                    @foreach ($promoCode->subscriptions as $subscription)
                        <a href="{{ URL::to('/subscription/show/' . $subscription->id) }}">
                            <li class="form-control text-center" style="background-color: lightgreen; margin: 3px;">
                                {{ $subscription->name }} 
                                - {{ $subscription->duration }} @lang('common.months_count')
                                - <strike>{{ $subscription->old_price }} zł</strike>
                                {{ $subscription->new_price }} zł
                            </li>
                        </a>
                    @endforeach
                </ul>
            </div> 
        @endif
        
        <div class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
            <h3 class="text-center">@lang('common.messages') :</h3>
            <hr>
            <div id="approve-button" class="text-center" style="padding-bottom: 1rem;">
                <a class="btn btn-info" href="{{ URL::to('/admin/approve/message/status/change/' . $promoCode->id) }}">
                    @if ($promoCode->boss->isApproved == 0)
                        @lang('common.approve')
                    @else
                        @lang('common.disapprove')
                    @endif
                </a>
            </div>
            @if (count($promoCode->messages) > 0)
                @foreach ($promoCode->messages as $message)
                    <div class="row">
                        @if ($message->owner_id == $promoCode->boss->id)
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6"></div>
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                                <div class="admin-message" data-message_id="{{$message->id}}">
                                    <div class="text-center">
                                        <p>{{$message->created_at}}</p>
                                    </div>
                                    <p>{{$message->text}}</p>
                                    <div class="text-right" style="padding-right: 3rem;">
                                        <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/promo-code/message/change-status/' . $promoCode->id . '/' . $message->id) }}">
                                            @if ($message->status == 0)
                                                @lang('common.mark_as_written')
                                            @elseif ($message->status == 1)
                                                @lang('common.mark_as_sent')
                                            @endif
                                        </a>
                                        {{config('message-status.' . $message->status)}}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                                <div class="boss-message" data-message_id="{{$message->id}}">
                                    <div class="text-center">
                                        <p>{{$message->created_at}}</p>
                                    </div>
                                    <p>{{$message->text}}</p>
                                    <div class="text-right" style="padding-right: 3rem;">
                                        {{config('message-status.' . $message->status)}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6"></div>
                        @endif
                    </div>
                @endforeach
            @endif
            <div class="row" style="margin-top: 2rem;">
                {{ Form::open(['id' => 'send-message', 'action' => ['AdminController@makeAPromoCodeMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}

                    <div class="form-group text-center">
                        <input type="text" name="text" value="{{ Input::old('text') }}" style="width: 60%;" placeholder="@lang('common.send_a_message')" autocomplete="off">
                    </div>

                    {{ Form::hidden('promo_code_id', $promoCode->id) }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.send')" class="btn btn-primary">
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div> 
</div>
@endsection