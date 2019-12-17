@extends('layouts.app')
@section('content')

{!! Html::style('css/graphic_request.css') !!}
{!! Html::script('js/graphic_request_show.js') !!}

    <div class="container">
        <h2 class="text-center" style="padding-top: 2rem;">@lang('common.graphic_request_from'): {{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</h2>
        <p class="text-center">{{$graphicRequest->property->name}} - {{$graphicRequest->boss->name}} {{$graphicRequest->boss->surname}}</p>
    </div>

    <div class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <p>@lang('common.start_time'): <strong>{{$graphicRequest->start_time}}</strong></p>
                <p>@lang('common.end_time'): <strong>{{$graphicRequest->end_time}}</strong></p>
                @if ($graphicRequest->comment)
                    <p>@lang('common.comment'): <strong>{{$graphicRequest->comment}}</strong></p>
                @endif
                <div id="employees" style="margin-top: 2rem;">
                    <p class="text-center">@lang('common.chosen_employees'):</p>
                    <ul style="padding: 12px;">
                        @foreach($graphicRequest->allEmployees as $employee)
                            @if ($employee->isChosen == true)
                                <li class="form-control" style="background-color: lightgreen;" data-active="true" value="{{$employee->id}}">
                                    {{$employee->name}} {{$employee->surname}}
                                    @if ($employee->showProperty !== false)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/property/' . $graphicRequest->property_id) }}">
                                            @lang('common.create_calendar')
                                        </a>
                                    @elseif ($employee->yearId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/year/show/' . $employee->yearId) }}">
                                            @lang('common.add_month')
                                        </a>
                                    @elseif ($employee->monthId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/month/show/' . $employee->monthId) }}">
                                            @lang('common.add_day')
                                        </a>
                                    @elseif ($employee->dayId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/day/show/' . $employee->dayId) }}">
                                            @lang('common.add_schedule')
                                        </a>
                                    @endif
                                </li>
                            @else
                                <li class="form-control" value="{{$employee->id}}">
                                    {{$employee->name}} {{$employee->surname}}
                                    @if ($employee->showProperty !== false)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/property/' . $graphicRequest->property_id) }}">
                                            @lang('common.create_calendar')
                                        </a>
                                    @elseif ($employee->yearId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/year/show/' . $employee->yearId) }}">
                                            @lang('common.add_month')
                                        </a>
                                    @elseif ($employee->monthId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/month/show/' . $employee->monthId) }}">
                                            @lang('common.add_day')
                                        </a>
                                    @elseif ($employee->dayId)
                                        <a class="btn pallet-2-4 btn-sm" style="padding: 1px; color: white;" href="{{ URL::to('/day/show/' . $employee->dayId) }}">
                                            @lang('common.add_schedule')
                                        </a>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <p>@lang('common.last_update'): 
                    <strong>
                        {{$graphicRequest->updated_at}}
                    </strong>
                </p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
<!--                <h1 class="text-center">todo: Place for something?</h1>-->
            </div>
        </div>
    </div>

    <div class="jumbotron" style="margin-left: 2rem; margin-right: 2rem;">
        <h3 class="text-center">@lang('common.messages'):</h3>
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
                                    <a class="btn pallet-1-3 btn-sm" style="color: white;" href="{{ URL::to('/admin/graphic-request/message/change-status/' . $graphicRequest->id . '/' . $message->id) }}">
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
                    <input type="text" name="text" value="{{ Input::old('text') }}" style="width: 60%;" placeholder="@lang('common.send_a_message')" autocomplete="off">
                </div>
            
                {{ Form::hidden('graphic_request_id', $graphicRequest->id) }}
            
                <div class="text-center">
                    <input type="submit" value="@lang('common.send')" class="btn pallet-2-4" style="color: white;">
                </div>

            {{ Form::close() }}
        </div>
    </div>
@endsection