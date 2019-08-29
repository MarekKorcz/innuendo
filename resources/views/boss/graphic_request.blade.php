@extends('layouts.app')
@section('content')

{!! Html::style('css/graphic_request.css') !!}
{!! Html::script('js/graphic_request_show.js') !!}

    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.graphic_request_from') : {{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</h2>
        <p class="text-center">@lang('common.regarding') : {{$graphicRequest->property->name}}</p>
        <div class="text-center" style="padding-bottom: 1rem;">
            <a class="btn btn-success" href="{{ URL::to('/boss/graphic-request/edit/' . $graphicRequest->id) }}">
                @lang('common.edit')
            </a>
        </div>
    </div>

    <div id="info" class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <p>@lang('common.start_time') : <strong>{{$graphicRequest->start_time}}</strong></p>
                <p>@lang('common.end_time') : <strong>{{$graphicRequest->end_time}}</strong></p>
                @if ($graphicRequest->comment)
                    <p>@lang('common.comment') : <strong>{{$graphicRequest->comment}}</strong></p>
                @endif
                <div id="employees" style="margin-top: 2rem;">
                    <p class="text-center">@lang('common.chosen_employees') :</p>
                    <ul style="padding: 12px;">
                        @foreach($graphicRequest->allEmployees as $employee)
                            @if ($employee->isChosen == true)
                                <li class="form-control" style="background-color: lightgreen;" data-active="true" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                            @else
                                <li class="form-control" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <p>@lang('common.last_update') : <strong>{{$graphicRequest->updated_at}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h1 class="text-center">Miejsce na coś?</h1>
            </div>
        </div>
    </div>

    <div id="messages" class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <div id="messages-head">
            <h3 class="text-center">@lang('common.messages') :</h3>
            <hr style="margin-bottom: 2rem;">
        </div>
        @if (count($graphicRequestMessages) > 0)
            @foreach ($graphicRequestMessages as $message)
                <div class="row" style="padding: 2px;">
                    @if ($message->owner_id == $graphicRequest->boss_id)
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            <div class="boss-message" data-message_id="{{$message->id}}">
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
                            <div class="admin-message" data-message_id="{{$message->id}}" data-status="{{$message->status}}">
                                <div class="text-center">
                                    <p>{{$message->created_at}}</p>
                                </div>
                                <p>{{$message->text}}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
            @if ($chosenMessage !== null)
                <div id="chosenMessageId" data-chosen_message_id="{{$chosenMessage->id}}"></div>
            @endif
        @endif
        <div class="row" style="margin-top: 2rem;">
            {{ Form::open(['id' => 'send-message', 'action' => ['BossController@makeAMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}
                
                <div class="form-group text-center">
                    <input id="text" type="text" style="width: 60%;" value="{{Input::old('text')}}" placeholder="@lang('common.send_a_message_to_us')" autocomplete="off">
                </div>
            
                {{ Form::hidden('graphic_request_id', $graphicRequest->id) }}
            
                <div class="text-center">
                    <input type="submit" value="@lang('common.send')" class="btn btn-primary">
                </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection