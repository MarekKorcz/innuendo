@extends('layouts.app')
@section('content')

{!! Html::style('css/graphic_request.css') !!}
<!--{!! Html::script('js/graphic_request_show.js') !!}-->

    <div id="title" class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.graphic_request_from'): {{$graphicRequest->day->day_number}} {{$graphicRequest->day->month->month}} {{$graphicRequest->day->month->year->year}}</h2>
        <p class="text-center">@lang('common.regarding'): {{$graphicRequest->property->name}}</p>
    </div>

    <div class="row">
        <div class="col-1"></div>
        <div class="col-10 text-center">
            <div id="info" class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
                <p>@lang('common.start_time'): <strong>{{$graphicRequest->start_time}}</strong></p>
                <p>@lang('common.end_time'): <strong>{{$graphicRequest->end_time}}</strong></p>
                @if ($graphicRequest->comment)
                    <p>@lang('common.comment'): <strong>{{$graphicRequest->comment}}</strong></p>
                @endif
                <div id="employees" style="margin-top: 2rem;">
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-8">
                            <p class="text-center">@lang('common.chosen_employees'):</p>
                            <ul style="padding: 12px;">
                                @foreach($graphicRequest->allEmployees as $employee)
                                    <a href="{{ URL::to('/employee/' . $employee->slug) }}" target="_blank">
                                        @if ($employee->isChosen == true)
                                            <li class="form-control" style="background-color: lightgreen;" data-active="true" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                                        @else
                                            <li class="form-control" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                                        @endif
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-2"></div>
                    </div>
                </div>
                <p>@lang('common.last_update'): <strong>{{$graphicRequest->updated_at}}</strong></p>
            </div>
        </div>
        <div class="col-1"></div>
    </div>

    <div class="row">
        <div class="col-1"></div>
        <div class="col-10 text-center">
            <div id="messages" class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
                <div id="messages-head">
                    <h3 class="text-center">@lang('common.messages'):</h3>
                    <hr style="margin-bottom: 2rem;">
                </div>
                @if (count($graphicRequest->messages) > 0)
                    @foreach ($graphicRequest->messages as $message)
                        <div class="row" style="padding: 2px;">
                            @if ($message->user->id == $graphicRequest->boss->id)
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
                @endif
                <div class="row" style="margin-top: 2rem;">
                    {{ Form::open(['id' => 'send-message', 'action' => ['BossController@makeAMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}

                        <div class="form-group text-center">
                            <input id="text" name="text" type="text" style="width: 60%;" value="{{Input::old('text')}}" placeholder="@lang('common.send_a_message_to_us')" autocomplete="off">
                        </div>

                        {{ Form::hidden('graphic_request_id', $graphicRequest->id) }}

                        <div class="text-center">
                            <input type="submit" value="@lang('common.send')" class="btn btn-primary">
                        </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
@endsection