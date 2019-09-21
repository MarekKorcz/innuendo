@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse" style="padding-top: 1rem;">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                @if ($month)
                    <a class="btn btn-success" href="{{ URL::to('month/show/' . $month->id) }}">
                        @lang('common.back_to_month')
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <div style="padding: 20px;" class="text-center">
        <h2><strong>{{$day->day_number}} / {{$month->month}} / {{$year->year}}</strong></h2>
        <h3>{{$property->name}}</h3>
        <h5>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}</h5>
    </div>
    
    @if ($graphicTime !== null)
        <div class="row" style="padding-bottom: 2rem;">
            <div class="offset-1"></div>
            <div class="col-10">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>                
                            <td>@lang('common.hours')</td>
                            <td>@lang('common.minutes_capital')</td>
                            <td>@lang('common.action')</td>
                        </tr>
                    </thead>
                    <tbody>         
                        <tr>
                            <td>{{$graphicTime->start_time}} - {{$graphicTime->end_time}}</td>
                            <td>{{$graphicTime->total_time}}</td>
                            <td>
                                <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $calendar->id . '/' . $year->year . '/' . $month->month_number . '/' . $day->day_number) }}">
                                    @lang('common.show')
                                </a>
                                <a class="btn btn-primary" href="{{ URL::to('graphic/' . $graphicTime->id . '/edit') }}">
                                    @lang('common.edit')
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="offset-1"></div>
        </div>
    @else
        <div class="text-center" style="padding-bottom: 2rem;">
            <a class="btn btn-success" href="{{ action('GraphicController@create', $day->id) }}">
                @lang('common.create_graphic')
            </a>
        </div>
    @endif
</div>
@endsection