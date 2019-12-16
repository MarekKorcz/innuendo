@extends('layouts.app')
@section('content')

{!! Html::style('css/backend_graphic.css') !!}

<div class="container">

    <div class="text-center" style="padding-top: 1rem;">
        <h2>@lang('common.schedule_in'):</h2>
    </div>

    <div class="row" style="padding-bottom: 1rem;">
        <div class="offset-1"></div>
        <div class="col-10">            
            <ul>
                @foreach($calendars as $calendar)  
                    <div id="schedules" class="text-center">
                        <a href="{{ URL::to('employee/backend-calendar/' . $calendar->id . '/0/0/0') }}">
                            <li>
                                <strong>
                                    {{$calendar->property->name}}
                                </strong>
                                -
                                {{$calendar->property->street}} {{$calendar->property->street_number}} / {{$calendar->property->house_number}}, {{$calendar->property->city}}
                            </li>
                        </a>
                    </div>
                @endforeach
            </ul>
        </div>
        <div class="offset-1"></div>
    </div>
</div>
@endsection