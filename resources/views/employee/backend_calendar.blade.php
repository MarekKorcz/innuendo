@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
{!! Html::script('js/calendar.js') !!}

<div class="container">
    
    <div style="padding: 1rem 0 0 2rem;">
        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/employee/backend-graphic') }}">
            @lang('common.go_back')
        </a>
    </div>

    <div id="calendar" class="table-responsive" style="padding-bottom: 2rem;">
        <div class="text-center" style="padding-bottom: 3px;">
            <h2>@lang('common.schedule_in') <strong>{{$property->name}}</strong></h2>
            <h4>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}</h4>
        </div>
        
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availablePreviousMonth && $month->month_number == 1)
                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . ($year->year - 1) . '/12/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @elseif ($availablePreviousMonth)
                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number - 1) . '/' . $current_day) }}">
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
                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . ($year->year + 1) . '/1/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @elseif ($availableNextMonth)
                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number + 1) . '/' . $current_day) }}">
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
                                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                        @if ($days[$i]->day_number == $current_day)
                                            <h4 class="marked">
                                        @else
                                            <h4>
                                        @endif
                                                {{$days[$i]->day_number}}
                                            </h4>
                                    </a>
                                @endif
                            </td>
                    @elseif ($i == 5 || $i == 11 || $i == 17 || $i == 23 || $i == 29 || $i == 35)
                            <td class="text-center">
                                @if (is_object($days[$i]) && $days[$i] !== null)
                                    <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                        @if ($days[$i]->day_number == $current_day)
                                            <h4 class="marked">
                                        @else
                                            <h4>
                                        @endif
                                                {{$days[$i]->day_number}}
                                            </h4>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @else
                        <td class="text-center">
                            @if (is_object($days[$i]) && $days[$i] !== null)
                                <a href="{{ URL::to('employee/backend-calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
                                    @if ($days[$i]->day_number == $current_day)
                                        <h4 class="marked">
                                    @else
                                        <h4>
                                    @endif
                                            {{$days[$i]->day_number}}
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
                <div class="col-1"></div>
                <div id="appointments" class="text-center col-10">
                    @if(count($graphic) > 0)
                        @for ($i = 0; $i < count($graphic); $i++)
                            @if ($graphic[$i]['appointment'] == null)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time']}}</div>
                                    <div class="appointment-term box-1 pallet-1-3">
                                        <div class="appointment-info">
                                            <a style="color: white;" href="#makeAnAppointment" data-toggle="modal" data-id="{{$graphic[$i]['time']}}" title="@lang('common.click_to_make_reservation')">
                                                @lang('common.available')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else   
                                <div class="appointment">
                                    @if ($graphic[$i]['appointment']->minutes == 15)
                                        <div class="box">{{$graphic[$i]['time']}}</div>
                                        <div class="appointment-term box-1 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @elseif ($graphic[$i]['appointment']->minutes == 30)
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="appointment-term box-2 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @elseif ($graphic[$i]['appointment']->minutes == 45)
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                        <div class="appointment-term box-3 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @elseif ($graphic[$i]['appointment']->minutes == 60)
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                        <div class="appointment-term box-4 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @elseif ($graphic[$i]['appointment']->minutes == 75)
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][4]}}</div>
                                        <div class="appointment-term box-5 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @elseif ($graphic[$i]['appointment']->minutes == 90)
                                        <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][4]}}</div>
                                        <div class="box">{{$graphic[$i]['time'][5]}}</div>
                                        <div class="appointment-term box-6 pallet-1-2">
                                            <div class="appointment-info">
                                                <a href="{{ URL::to('/employee/backend-appointment/show/' . $graphic[$i]['appointment']->id) }}" style="color: white;">
                                                    @if ($graphic[$i]['appointment']->user)
                                                        {{$graphic[$i]['appointment']->user->name}} -
                                                    @else
                                                        {{$graphic[$i]['appointment']->tempUser->name}} -
                                                    @endif
                                                    {{$graphic[$i]['appointment']->item->name}} -
                                                    {{config('appointment-status.' . $graphic[$i]['appointment']->status)}}
                                                </a>
                                            </div>
                                        </div>
                                    @endif          
                                </div>
                            @endif
                        @endfor
                    @else
                        <h3 class="pallet-2-1-font" style="padding: 40px;">@lang('common.had_not_sent_graphic_request')</h3>
                    @endif
                </div>
                <div class="col-1"></div>
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
                
                {{ Form::open(['action' => 'WorkerController@beforeShowCreatePage', 'method' => 'POST']) }}

                    <div class="form-group">
                        <label name="appointmentTerm" for="appointmentTerm"></label>
                        <input type="hidden" name="appointmentTerm" id="appointmentTerm" value=""/>
                        @if($graphic_id !== null)
                            <input type="hidden" name="graphicId" value="{{$graphic_id}}"/>
                        @endif
                        <input type="hidden" name="calendarId" value="{{$calendar_id}}"/>
                        <input type="hidden" name="year" value="{{$year->year}}"/>
                        <input type="hidden" name="month" value="{{$month->month_number}}"/>
                        <input type="hidden" name="day" value="{{$current_day}}"/>
                    </div>
                 
                    <input type="submit" value="@lang('common.go_to_reservation')" class="btn pallet-1-3" style="color: white;">

                {{ Form::close() }}
                 
            </div>
        </div>
    </div>
</div>
@endsection