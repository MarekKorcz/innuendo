@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

<div class="container">
    <div id="calendar" class="table-responsive">
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @svg('solid/angle-left')
            </div>
            <div class="head-tile" style="width: 50%;">
                <div class="text-center">
                    <h2>{{$year->year}}</h2>
                    <h3>{{$month->month}}</h3>
                </div>
            </div>
            <div class="head-tile text-center" style="width: 25%; padding-top: 30px;">
                @svg('solid/angle-right')
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
                                    @if ($days[$i]->day_number == $current_day)
                                        <h4 class="marked">
                                    @else
                                        <h4>
                                    @endif
                                            {{$days[$i]->day_number}}
                                        </h4>
                                @endif
                            </td>
                    @elseif ($i == 5 || $i == 11 || $i == 17 || $i == 23 || $i == 29 || $i == 35)
                            <td class="text-center">
                                @if (count($days[$i]) > 0)
                                    @if ($days[$i]->day_number == $current_day)
                                        <h4 class="marked">
                                    @else
                                        <h4>
                                    @endif
                                            {{$days[$i]->day_number}}
                                        </h4>
                                @endif
                            </td>
                        </tr>
                    @else
                        <td class="text-center">
                            @if (count($days[$i]) > 0)
                                @if ($days[$i]->day_number == $current_day)
                                    <h4 class="marked">
                                @else
                                    <h4>
                                @endif
                                        {{$days[$i]->day_number}}
                                    </h4>
                            @endif
                        </td>
                    @endif
                    
                    @if ($i == count($days))
                        </tr>
                    @endif
                @endfor
            </tbody>
        </table>
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                <div id="appointments" class="text-center col-lg-10 col-md-10 col-sm-10 col-xs-10">
<!--                    @if (count($graphic) > 0)
                        <table class="table table-striped" style="margin-top: 20px;">
                            <tr>
                                <th>Hours</th>
                                <th class="text-center">Appointments</th>
                            </tr>
                            <div class="list-group">
                                <tr>
                                    @for ($i = 0; $i < count($graphic); $i++)
                                        <tr>
                                            <td>
                                                {{$graphic[$i][0]}}
                                            </td>
                                            <td style="height: 120px;">
                                                <div class="text-center">
                                                    {{$graphic[$i][1]}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endfor
                                </tr>
                            </div>
                        </table>
                    @endif
                    @if (count($graphic) == 0)
                        <div class="text-center" style="padding-top: 50px;">
                            <p>There's no Graphic available this day</p>
                        </div>
                    @endif-->

                    <div class="appointment">
                        <div class="box">Box 1</div>
                        <div class="box-1">Box 2</div>
                    </div>
                    <div class="appointment">
                        <div class="box">Box 1</div>
                        <div class="box">Box 2</div>
                        <div class="box-2">Box 3</div>
                    </div>
                    <div class="appointment">
                        <div class="box">Box 1</div>
                        <div class="box">Box 2</div>
                        <div class="box">Box 3</div>
                        <div class="box-3">Box 4</div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            </div>
        </div>
    </div>
</div>
@endsection