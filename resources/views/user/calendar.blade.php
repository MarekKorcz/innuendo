@extends('layouts.app')

@section('content')

{!! Html::script('js/calendar.js') !!}
{!! Html::style('css/calendar.css') !!}

<div class="container" style="padding-bottom: 2rem;">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-primary" href="{{ URL::to('/appointment/index') }}">
                @lang('common.all_massages')
            </a>
        </div>
        <div class="col-4">
            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/employee/' . $employee_slug) }}">
                @lang('common.back_to_employee')
            </a>
        </div>
    </div>

    <div id="calendar" class="table-responsive">
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availablePreviousMonth && $month->month_number == 1)
                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . ($year->year - 1) . '/12/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @elseif ($availablePreviousMonth)
                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number - 1) . '/' . $current_day) }}">
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
                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . ($year->year + 1) . '/1/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @elseif ($availableNextMonth)
                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number + 1) . '/' . $current_day) }}">
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
                                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                                    <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                                <a href="{{ URL::to('user/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                <div class="col-1"></div>
                <div id="appointments" class="col-10 text-center">
                    @if(count($graphic) > 0)
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
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-1 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
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
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-2 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
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
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-3 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
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
                            @elseif ($graphic[$i]['appointmentLimit'] == 4)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-4 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
                                                        @lang('common.appointment_details')
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="appointment-term box-4 pallet-2-2">
                                                <div class="appointment-info">
                                                    @lang('common.booked')
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @elseif ($graphic[$i]['appointmentLimit'] == 5)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][4]}}</div>
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-5 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
                                                        @lang('common.appointment_details')
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="appointment-term box-5 pallet-2-2">
                                                <div class="appointment-info">
                                                    @lang('common.booked')
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @elseif ($graphic[$i]['appointmentLimit'] == 6)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][3]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][4]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][5]}}</div>
                                    @if ($graphic[$i]['appointmentId'] !== 0)
                                        @if ($graphic[$i]['appointment']->user->id == auth()->user()->id)
                                            <div class="appointment-term box-6 pallet-1-2">
                                                <div class="appointment-info">
                                                    <a style="color: white;" href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}">
                                                        @lang('common.appointment_details')
                                                    </a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="appointment-term box-6 pallet-2-2">
                                                <div class="appointment-info">
                                                    @lang('common.booked')
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        @endfor
                    @else
                        <h3 class="pallet-2-1-font" style="padding: 40px;">
                            @lang('common.no_schedule')
                        </h3>
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
                {{ Form::open(['action' => 'AppointmentController@beforeShowCreatePage', 'method' => 'POST']) }}

                    <div class="form-group">
                        <label name="appointmentTerm" for="appointmentTerm"></label>
                        <input id="appointmentTerm" type="hidden" name="appointmentTerm" value=""/>
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