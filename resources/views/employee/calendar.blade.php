@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

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
                                    <div class="box-1" style="background-color: lightgreen;">Wolne</div>
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 1)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time']}}</div>
                                    <div class="box-1">Zajęte</div>
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 2)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box-2">Zajęte</div>
                                </div>
                            @elseif ($graphic[$i]['appointment'] == 3)
                                <div class="appointment">
                                    <div class="box">{{$graphic[$i]['time'][0]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][1]}}</div>
                                    <div class="box">{{$graphic[$i]['time'][2]}}</div>
                                    <div class="box-3">Zajęte</div>
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
</div>
@endsection