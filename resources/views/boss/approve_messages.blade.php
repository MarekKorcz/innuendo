@extends('layouts.app')
@section('content')

{!! Html::style('css/approve_messages.css') !!}
{!! Html::script('js/approve_messages.js') !!}

    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">Wiadomości dotyczące {{$promoCode->promo->title}}</h2>
    </div>

    <div id="messages" class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
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
                    {{ Form::text('text', Input::old('text'), array('id' => 'text', 'style' => 'width: 60%;', 'placeholder' => 'Napisz do nas wiadomość', 'autocomplete' => 'off')) }}
                </div>
            
                {{ Form::hidden('promo_code_id', $promoCode->id) }}
            
                <div class="text-center">
                    {{ Form::submit('Wyślij', array('class' => 'btn btn-primary')) }}
                </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection