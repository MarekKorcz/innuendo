@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
{!! Html::script('js/calendar.js') !!}

<div class="container">
    
    <a class="btn btn-primary" href="{{ URL::to('/employee/' . $employee_slug) }}">
        Back to Employee
    </a>

    <div id="calendar" class="table-responsive">
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availablePreviousMonth && $month->month_number == 1)
                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . ($year->year - 1) . '/12/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @elseif ($availablePreviousMonth)
                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number - 1) . '/' . $current_day) }}">
                        @svg('solid/angle-left')
                    </a>
                @endif
            </div>
            <div class="head-tile" style="width: 50%;">
                <div class="text-center">
                    <h2>{{$year->year}}</h2>
                    <h3>{{$month->month}}</h3>
                </div>
            </div>
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @if ($availableNextMonth && $month->month_number == 12)
                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . ($year->year + 1) . '/1/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @elseif ($availableNextMonth)
                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . $year->year . '/' . ($month->month_number + 1) . '/' . $current_day) }}">
                        @svg('solid/angle-right')
                    </a>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
        <table class="table">
            <thead>
                <tr id="days">
                    <th class="text-center">Pon</th>
                    <th class="text-center">Wt</th>
                    <th class="text-center">Śr</th>
                    <th class="text-center">Czw</th>
                    <th class="text-center">Pt</th>
                    <th class="text-center">Sob</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < count($days); $i++)
                    @if ($i == 0 || $i == 6 || $i == 12 || $i == 18 || $i == 24 || $i == 30 || $i == 36)
                        <tr>
                            <td class="text-center">
                                @if (count($days[$i]) > 0)
                                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                                @if (count($days[$i]) > 0)
                                    <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                            @if (count($days[$i]) > 0)
                                <a href="{{ URL::to('employee/calendar/' . $calendar_id . '/' . $year->year . '/' . $month->month_number . '/' . $days[$i]->day_number) }}">
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
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                <div id="appointments" class="text-center col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    @if(count($graphic))
                        @for ($i = 0; $i < count($graphic); $i++)
                            @if ($graphic[$i]['appointment'] == 0)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time']}}</div>
                                    <a href="#makeAnAppointment" data-toggle="modal" data-id="{{$graphic[$i]['time']}}" title="Kliknij by rozpocząć rezerwacje" class="appointment-term box-1" style="background-color: lightgreen;">
                                        <p style="margin-top: 15px;">
                                            Wolne
                                        </p>
                                    </a>
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 1)                            
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time']}}</div>
                                    @if ($graphic[$i]['appointmentId'] == 0)
                                        <div class="box-1">
                                            Zajęte
                                        </div>
                                    @elseif ($graphic[$i]['appointmentId'] > 0)
                                        <a href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}" class="appointment-term box-1">
                                            <p style="margin-top: 15px;">
                                                Szczegóły wizyty
                                            </p>
                                        </a>
                                    @endif
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 2)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    @if ($graphic[$i]['appointmentId'] == 0)
                                        <div class="box-2">
                                            Zajęte
                                        </div>
                                    @elseif ($graphic[$i]['appointmentId'] > 0)
                                        <a href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}" class="appointment-term box-2">
                                            <p style="margin-top: 45px;">
                                                Wizyta użytkownika
                                            </p>
                                        </a>
                                    @endif
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 3)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                    @if ($graphic[$i]['appointmentId'] == 0)
                                        <div class="box-3">
                                            Zajęte
                                        </div>
                                    @elseif ($graphic[$i]['appointmentId'] > 0)
                                        <a href="{{ URL::to('/appointment/show/' . $graphic[$i]['appointmentId']) }}" class="appointment-term box-3">
                                            <p style="margin-top: 72px;">
                                                Wizyta użytkownika
                                            </p>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endfor
                    @else
                        <h3 style="padding: 40px; color: coral;">Ten dzień nie posiada otwartego grafiku</h3>
                    @endif
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            </div>
        </div>
    </div>
    
    <div class="modal hide" id="makeAnAppointment">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Rezerwacja wizyty</h3>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">                
                
                {{ Form::open(['action' => 'AppointmentController@beforeShowCreatePage', 'method' => 'POST']) }}

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
                 
                    {{ Form::submit('Przejdz do rezerwacji', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
                 
            </div>
        </div>
    </div>
</div>
@endsection