@extends('layouts.app')
@section('content')

{!! Html::style('css/approve_messages.css') !!}
{!! Html::script('js/approve_messages.js') !!}

<div class="container">
    
    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.messages_between_admin_and') {{$promoCode->boss->name}} {{$promoCode->boss->surname}} ( {{$promoCode->promo->title}} )</h2>
        <div id="approve-button" class="text-center" style="padding-top: 1rem; padding-bottom: 1rem;">
            <a class="btn btn-info" href="{{ URL::to('/admin/approve/message/status/change/' . $promoCode->id) }}">
                @if ($promoCode->boss->is_approved == 0)
                    @lang('common.approve')
                @else
                    @lang('common.disapprove')
                @endif
            </a>
        </div>
    </div>

    <div id="messages" class="jumbotron">
        @if (count($promoCode->messages) > 0)
            @foreach ($promoCode->messages as $key => $message)
                <div class="row" style="padding: 2px;">
                    @if ($message->user_id == $admin->id)
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
            {{ Form::open(['id' => 'send-message', 'action' => ['AdminController@makeAnApproveMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}
                
                <div class="form-group text-center">
                    <input id="text" type="text" name="text" value="{{ Input::old('text') }}" style="width: 60%;" placeholder="@lang('common.reply')" autocomplete="off">
                </div>
            
                {{ Form::hidden('promo_code_id', $promoCode->id) }}
                {{ Form::hidden('boss_id', $promoCode->boss->id) }}
            
                <div class="text-center">
                    <input type="submit" value="@lang('common.send')" class="btn pallet-2-4" style="color: white;">
                </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection