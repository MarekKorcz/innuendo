@extends('layouts.app')
@section('content')

{!! Html::style('css/approve_messages.css') !!}
{!! Html::script('js/approve_messages.js') !!}

<div class="container">
    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.messages_regarding'): {{$promoCode->promo->title}}</h2>
    </div>

    <div id="subscriptions" class="jumbotron">
        <div class="row text-center">
            <div class="col-1"></div>
            <div class="col-10">
                @if (count($promoCode->subscriptions) > 0)
                    @foreach($promoCode->subscriptions as $subscription)
                        <div>
                            <h4>{!! $subscription->name !!}</h4>
                            <h5>{!! $subscription->description !!}</h5>
                            <p>@lang('common.old_price'): <strong>{{ $subscription->old_price }}</strong></p>
                            <p>@lang('common.new_price'): <strong>{{ $subscription->new_price }}</strong></p>
                            <p>@lang('common.appointment_quantity_per_month'): <strong>{{ $subscription->quantity }}</strong> @lang('common.per_person_2')</p>
                            <p>@lang('common.how_many_months_since_start'): <strong>{{ $subscription->duration }}</strong></p>
                            <p>@lang('common.worker_quantity'): 
                                <strong>
                                    @if ($subscription->worker_quantity == 0)
                                        @lang('common.infinity')
                                    @elseif ($subscription->worker_quantity !== null)
                                        {{ $subscription->worker_quantity }}
                                    @endif
                                </strong>
                            </p>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    <div id="messages" class="jumbotron">
        @if (count($promoCode->messages) > 0)
            @foreach ($promoCode->messages as $key => $message)
                <div class="row" style="padding: 2px;">
                    @if ($message->owner_id == $boss->id)
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            @if ($key + 1 == count($promoCode->messages))
                                <div class="boss-message" data-message_id="{{$message->id}}" data-last="true">
                            @else
                                <div class="boss-message" data-message_id="{{$message->id}}">
                            @endif
                                <div class="text-center">
                                    <p>{{$message->created_at}}</p>
                                </div>
                                <p>{{$message->text}}</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6"></div>
                    @else
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6"></div>
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            @if ($key + 1 == count($promoCode->messages))
                                <div class="admin-message" data-message_id="{{$message->id}}" data-status="{{$message->status}}" data-last="true">
                            @else
                                <div class="admin-message" data-message_id="{{$message->id}}" data-status="{{$message->status}}">
                            @endif
                                <div class="text-center">
                                    <p>{{$message->created_at}}</p>
                                </div>
                                <p>{{$message->text}}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
        <div class="row" style="margin-top: 2rem;">
            {{ Form::open(['id' => 'send-message', 'action' => ['BossController@makeAnApproveMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}
                
                <div class="form-group text-center">
                    <input id="text" name="text" type="text" style="width: 60%;" value="{{Input::old('text')}}" placeholder="@lang('common.send_a_message_to_us')" autocomplete="off">
                </div>
            
                {{ Form::hidden('promo_code_id', $promoCode->id) }}
            
                <div class="text-center">
                    <input type="submit" value="@lang('common.send')" class="btn btn-primary btn-lg">
                </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection