@extends('layouts.app')

@section('content')

{!! Html::script('js/boss_calendar.js') !!}
{!! Html::script('js/graphic_request.js') !!}
{!! Html::style('css/calendar.css') !!}

<div class="container" style="padding-bottom: 2rem;">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/boss/worker/appointment/list/' . $property->id) }}">
                @lang('common.all_appointments')
            </a>
        </div>
        <div class="col-4"></div>
    </div>

    <div id="calendar" class="table-responsive">
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availablePreviousMonth && $month->month_number == 1)
                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . ($year->year - 1) . '/12/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @elseif ($availablePreviousMonth)
                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . $year->year . '/' . ($month->month_number - 1) . '/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @endif
            </div>
            <div class="head-tile" style="width: 50%;">
                <div class="text-center">
                    <h2>{{$year->year}}</h2>
                    <h3>
                        @if (Session('locale') == "en")
                            {{ $month->month_en }}
                        @else
                            {{ $month->month }}
                        @endif
                    </h3>
                </div>
            </div>
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availableNextMonth && $month->month_number == 12)
                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . ($year->year + 1) . '/1/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @elseif ($availableNextMonth)
                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . $year->year . '/' . ($month->month_number + 1) . '/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
        <table class="table">
            <thead>
                <tr id="days">
                    <th class="text-center">@lang('common.monday_abbreviation')</th>
                    <th class="text-center">@lang('common.thuesday_abbreviation')</th>
                    <th class="text-center">@lang('common.wednesday_abbreviation')</th>
                    <th class="text-center">@lang('common.thursday_abbreviation')</th>
                    <th class="text-center">@lang('common.friday_abbreviation')</th>
                    <th class="text-center">@lang('common.saturday_abbreviation')</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < count($days); $i++)
                    @if ($i == 0 || $i == 6 || $i == 12 || $i == 18 || $i == 24 || $i == 30 || $i == 36)
                        <tr>
                            <td class="text-center">
                                @if (is_object($days[$i]) && $days[$i] !== null)
                                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                        @if ($days[$i]->day_number == $current_day)
                                            <h4 class="marked">
                                        @else
                                            <h4>
                                        @endif
                                                @if ($days[$i]->dayGraphicCount > 0)
                                                    <span class="pallet-2-2-font">
                                                       {{$days[$i]->day_number}}
                                                    </span>
                                                @else
                                                    {{$days[$i]->day_number}}
                                                @endif
                                            </h4>
                                    </a>
                                @endif
                            </td>
                    @elseif ($i == 5 || $i == 11 || $i == 17 || $i == 23 || $i == 29 || $i == 35)
                            <td class="text-center">
                                @if (is_object($days[$i]) && $days[$i] !== null)
                                    <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                        @if ($days[$i]->day_number == $current_day)
                                            <h4 class="marked">
                                        @else
                                            <h4>
                                        @endif
                                                @if ($days[$i]->dayGraphicCount > 0)
                                                    <span class="pallet-2-2-font">
                                                       {{$days[$i]->day_number}}
                                                    </span>
                                                @else
                                                    {{$days[$i]->day_number}}
                                                @endif
                                            </h4>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @else
                        <td class="text-center">
                            @if (is_object($days[$i]) && $days[$i] !== null)
                                <a href="{{ URL::to('boss/calendar/' . $property->id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                    @if ($days[$i]->day_number == $current_day)
                                        <h4 class="marked">
                                    @else
                                        <h4>
                                    @endif
                                            @if ($days[$i]->dayGraphicCount > 0)
                                                <span class="pallet-2-2-font">
                                                   {{$days[$i]->day_number}}
                                                </span>
                                            @else
                                                {{$days[$i]->day_number}}
                                            @endif
                                        </h4>
                                </a>
                            @endif
                        </td>
                    @endif

                    @if ($i == count($days))
                        </tr>
                    @endif
                @endfor
            </tbody>
        </table>
        <div id="appointments-container" class="container">
            <div class="row">
                <div class="col-0 col-md-1"></div>
                <div id="appointments" class="col-12 col-md-10 text-center">
                    @if(count($graphic) > 0)
                    
                        <div id="graphic-employees-buttons" data-current_day_id="{{$current_day_id}}" style="padding: 1rem;">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @foreach ($graphicTimesEntites as $key => $graphicTimeEntity)
                                    @if ($key == 0)
                                        <button data-graphic_id="{{$graphicTimeEntity->id}}" type="button" class="btn btn-success btn-lg">
                                    @else
                                        <button data-graphic_id="{{$graphicTimeEntity->id}}" type="button" class="btn btn-info btn-lg">
                                    @endif
                                        {{$graphicTimeEntity->employee->name}} {{$graphicTimeEntity->employee->surname}}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <hr>
                        
                        <div id="graphic">
                            @for ($i = 0; $i < count($graphic); $i++)
                                @if ($graphic[$i]['appointmentLimit'] == 0)
                                    <div class="appointment">
                                        <div class="box">{{$graphic[$i]['time']}}</div>
                                        @if ($graphic[$i]['canMakeAnAppointment'])
                                            <div class="appointment-term box-1 pallet-1-3">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="#makeAnAppointment" data-toggle="modal" data-id="{{$graphic[$i]['time']}}" title="@lang('common.click_to_make_reservation')">
                                                        @lang('common.available')
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="appointment-term box-1 pallet-1-4">
                                                <div class="appointment-info">
                                                    @lang('common.available')
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @elseif ($graphic[$i]['appointmentLimit'] == 1)                            
                                    <div class="appointment">
                                        <div class="box">{{$graphic[$i]['time']}}</div>
                                        @if ($graphic[$i]['appointment'] !== 0)
                                            @if ($graphic[$i]['bossWorkerAppointment'])
                                                <div class="appointment-term box-1 pallet-2-2">
                                                    <div class="appointment-info">
                                                        {{ $graphic[$i]['appointment']->user->name }} {{ $graphic[$i]['appointment']->user->surname }}
                                                    </div>
                                                </div>
                                            @elseif ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                                <div class="appointment-term box-1 pallet-1-2">
                                                    <div class="appointment-info">
                                                        <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointment']->id) }}">
                                                            @lang('common.appointment_details')
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="appointment-term box-1 pallet-2-2">
                                                    <div class="appointment-info">
                                                        @lang('common.booked')
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @elseif ($graphic[$i]['appointmentLimit'] == 2)
                                    <div class="appointment">
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        @if ($graphic[$i]['appointment'] !== 0)
                                            @if ($graphic[$i]['bossWorkerAppointment'])
                                                <div class="appointment-term box-2 pallet-2-2">
                                                    <div class="appointment-info">
                                                        {{ $graphic[$i]['appointment']->user->name }} {{ $graphic[$i]['appointment']->user->surname }}
                                                    </div>
                                                </div>
                                            @elseif ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                                <div class="appointment-term box-2 pallet-1-2">
                                                    <div class="appointment-info">
                                                        <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointment']->id) }}">
                                                            @lang('common.appointment_details')
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="appointment-term box-2 pallet-2-2">
                                                    <div class="appointment-info">
                                                        @lang('common.booked')
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @elseif ($graphic[$i]['appointmentLimit'] == 3)
                                    <div class="appointment">
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                        @if ($graphic[$i]['appointment'] !== 0)
                                            @if ($graphic[$i]['bossWorkerAppointment'])
                                                <div class="appointment-term box-3 pallet-2-2">
                                                    <div class="appointment-info">
                                                        {{ $graphic[$i]['appointment']->user->name }} {{ $graphic[$i]['appointment']->user->surname }}
                                                    </div>
                                                </div>
                                            @elseif ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                                <div class="appointment-term box-3 pallet-1-2">
                                                    <div class="appointment-info">
                                                        <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointment']->id) }}">
                                                            @lang('common.appointment_details')
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="appointment-term box-3 pallet-2-2">
                                                    <div class="appointment-info">
                                                        @lang('common.booked')
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endfor
                        </div>
                    @else
                        @if ($canSendRequest)
                            @if ($graphicRequest !== null)
                                <h3 style="padding-top: 2rem; padding-bottom: 1rem;">@lang('common.already_sent_graphic_request')</h3>
                                <a href="{{ URL::to('/boss/graphic-request/' . $graphicRequest->id . '/0') }}" 
                                   class="btn btn-success btn-lg" 
                                   style="color: white;"
                                >
                                    @lang('common.show')
                                </a>
                            @else
                                <h3 class="pallet-1-4-font" style="padding-top: 2rem; padding-bottom: 1rem;">@lang('common.send_graphic_request')</h3>
                                <a href="#makeAGraphicRequest" 
                                   id="request-btn" 
                                   class="btn btn-lg pallet-1-3" 
                                   style="color: white;"
                                   data-toggle="modal"
                                >
                                    @lang('common.send')
                                </a>
                            @endif
                        @else
                            <h3 class="pallet-2-1-font" style="padding: 40px;">@lang('common.had_not_sent_graphic_request')</h3>
                        @endif
                    @endif
                </div>
                <div class="col-0 col-md-1"></div>
            </div>
        </div>
    </div>
    
    <div class="modal hide" id="makeAnAppointment">
        <div class="modal-content">
            <div class="modal-header">
                <h3>@lang('common.appointment_reservation')</h3>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">         
                {{ Form::open(['action' => 'AppointmentController@beforeShowCreatePage', 'method' => 'POST']) }}

                    <div class="form-group">
                        <label name="appointmentTerm" for="appointmentTerm"></label>
                        <input id="appointmentTerm" type="hidden" name="appointmentTerm" value=""/>
                        @if($graphic_id !== null)
                            <input type="hidden" name="graphicId" value="{{$graphic_id}}"/>
                        @endif
                    </div>
                 
                    <input type="submit" value="@lang('common.go_to_reservation')" class="btn pallet-1-3" style="color: white;">

                {{ Form::close() }}
            </div>
        </div>
    </div>
    
    <div id="makeAGraphicRequest" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-center">@lang('common.send_graphic_request')</h2>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                {{ Form::open(['id' => 'request-form', 'action' => 'BossController@makeAGraphicRequest', 'method' => 'POST', 'novalidate' => true]) }}

                        <div class="row text-center">
                            <div class="col-2"></div>
                            <div class="col-8">
                                <div class="form-group">
                                    <legend>
                                        <label for="start_time" style="padding-left: 9px;">@lang('common.start_time'):</label>
                                        <br>
                                        <input class="timepicker" name="start_time" value="{{ Input::old('start_time') }}">
                                        <div class="warning" style="margin: 6px 0 0 6px;"></div>
                                    </legend>
                                </div>
                                <div class="form-group">
                                    <legend>
                                        <label for="end_time" style="padding-left: 9px;">@lang('common.end_time'):</label>
                                        <br>
                                        <input class="timepicker" name="end_time" value="{{ Input::old('end_time') }}">
                                        <div class="warning" style="margin: 6px 0 0 6px;"></div>
                                    </legend>
                                </div>
                                <div id="appointment-quantity-counter"></div>
                            </div>
                            <div class="col-2"></div>
                        </div>
                
                        <div class="row text-center">
                            <div class="col-1"></div>
                            <div class="col-10">
                                @if (count($employees) > 0)
                                    <h4 class="text-center">@lang('common.choose_from_within_our_employees'):</h4>
                                    <ul id="employees" style="padding: 12px 12px 0 12px;">
                                        @foreach($employees as $employee)
                                            @if(count($employees) == 1)
                                                <li class="form-control text-center" style="background-color: #A1D6AC; margin: 3px;" data-active="true" value="{{$employee->id}}">
                                                    {{$employee->name}} {{$employee->surname}}
                                                </li>
                                            @else
                                                <li class="form-control" value="{{$employee->id}}" style="margin: 3px;">
                                                    {{$employee->name}} {{$employee->surname}}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <div id="employees-warning" class="warning" style="margin: 6px 0 0 18px;"></div>
                                @endif

                                <div class="form-group">
                                    <label for="comment" style="padding-left: 21px;">@lang('common.comment'):</label>
                                    {{ Form::textarea('comment', Input::old('comment'), array('class' => 'form-control')) }}
                                </div>

                                <input type="hidden" name="day" value="{{$current_day_id}}"/>
                                @if(count($employees) == 1)
                                    @foreach($employees as $employee)
                                        <input type="hidden" name="employees[]" value="{{$employee->id}}"/>
                                    @endforeach
                                @endif

                                <div class="text-center">
                                    <input type="submit" value="@lang('common.go_to_reservation')" class="btn pallet-1-3" style="color: white;">
                                </div>
                            </div>
                            <div class="col-1"></div>
                        </div>                

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection