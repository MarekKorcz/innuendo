@extends('layouts.app')
@section('content')

{!! Html::style('css/graphic_request.css') !!}
{!! Html::script('js/graphic_request_show.js') !!}

    <div class="container">
        <h2 class="text-center" style="padding-top: 2rem;">Grafik request from: {{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</h2>
        <p class="text-center">{{$graphicRequest->property->name}} - {{$graphicRequest->boss->name}} {{$graphicRequest->boss->surname}}</p>
    </div>

    <div class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <p>From: <strong>{{$graphicRequest->start_time}}</strong></p>
                <p>To: <strong>{{$graphicRequest->end_time}}</strong></p>
                @if ($graphicRequest->comment)
                    <p>Comment: <strong>{{$graphicRequest->comment}}</strong></p>
                @endif
                <div id="employees" style="margin-top: 2rem;">
                    <p class="text-center">Chosen employees:</p>
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
                <p>Date of last modification: <strong>{{$graphicRequest->updated_at}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h1 class="text-center">Place for something?</h1>
            </div>
        </div>
    </div>

    <div class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <h3 class="text-center">Messages:</h3>
        <hr style="margin-bottom: 2rem;">
        @if (count($graphicRequestMessages) > 0)
            @foreach ($graphicRequestMessages as $message)
                <div class="row">
                    @if ($message->owner_id == auth()->user()->id)
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
                    @else
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6"></div>
                        <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                            <div class="admin-message" data-message_id="{{$message->id}}">
                                <div class="text-center">
                                    <p>{{$message->created_at}}</p>
                                </div>
                                <p>{{$message->text}}</p>
                                <div class="text-right" style="padding-right: 3rem;">
                                    <a class="btn btn-success btn-sm" href="{{ URL::to('/admin/graphic-request/message/change-status/' . $graphicRequest->id . '/' . $message->id) }}">
                                        @if ($message->status == 0)
                                            Mark as written
                                        @elseif ($message->status == 1)
                                            Mark as sended
                                        @endif
                                    </a>
                                    {{config('message-status.' . $message->status)}}
                                </div>
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
            {{ Form::open(['id' => 'send-message', 'action' => ['AdminController@makeAMessage'], 'method' => 'POST', 'style'=>'width: 100%;']) }}
                
                <div class="form-group text-center">
                    {{ Form::text('text', Input::old('text'), array('style' => 'width: 60%;', 'placeholder' => 'Send a message')) }}
                </div>
            
                {{ Form::hidden('graphic_request_id', $graphicRequest->id) }}
            
                <div class="text-center">
                    {{ Form::submit('Send', array('class' => 'btn btn-primary')) }}
                </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection