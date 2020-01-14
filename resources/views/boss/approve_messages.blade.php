@extends('layouts.app')
@section('content')

{!! Html::style('css/approve_messages.css') !!}
{!! Html::script('js/approve_messages.js') !!}

<div class="container">
    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.messages_regarding'): {{$promoCode->promo->title}}</h2>
    </div>

    <div id="messages" class="jumbotron">
        @if (count($promoCode->messages) > 0)
            @foreach ($promoCode->messages as $key => $message)
                <div class="row" style="padding: 2px;">
                    @if ($message->user_id == $promoCode->boss->id)
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
                    <input type="submit" value="@lang('common.send')" class="btn pallet-1-3 btn-lg" style="color: white;">
                </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection