@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}
{!! Html::script('js/worker_appointment_list.js') !!}

<div class="container">
    
    <div class="row" style="padding-top: 1rem;">
        <div class="col-12">
            <h1 class="text-center">
                @if ($property !== null)
                    @lang('common.massages_in')                
                    {{$property->name}}
                @endif
            </h1>
        </div>
    </div>
    
    <div class="row" style="padding-bottom: 1rem;">
        <div class="col-1"></div>
        <div class="col-10">
            @if (count($appointments) > 0)
                <div id="workers-panel" class="wrapper cont">
                    <div class="text-center">
                        <label for="search">@lang('common.write_your_name_and_lastname'):</label>
                        @if($worker !== null)
                            <input id="search" class="form-control" type="text" value="{{$worker->name . " " . $worker->surname}}" data-user_id="{{$worker->id}}" autocomplete="off">
                        @else
                            <input id="search" class="form-control" type="text" value="" autocomplete="off">          
                        @endif
                        <ul id="result" class="list-group list-styling"></ul>
                    </div>
                    <div class="text-center">
                        <label for="timePeriod">@lang('common.select_a_billing_period'):</label>
                        <select id="timePeriod" class="form-control" data-property_id="{{$property->id}}">
                            @foreach ($intervals as $interval)
                                @if ($interval['start_date'] == $currentInterval['start_date'])
                                    <option data-month_id="{{$interval['month_id']}}" selected>{{$interval['start_date']->format('Y-m-d')}} - {{$interval['end_date']->format('Y-m-d')}}</option>
                                @else
                                    <option data-month_id="{{$interval['month_id']}}">{{$interval['start_date']->format('Y-m-d')}} - {{$interval['end_date']->format('Y-m-d')}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            
                <div id="appointments-table">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.date')</td>
                                <td>@lang('common.hour')</td>
                                <td>@lang('common.name_and_surname')</td>
                                <td>@lang('common.massage')</td>
                                <td>@lang('common.executor')</td>
                                <td>@lang('common.status')</td>
                            </tr>
                        </thead>
                        <tbody id="appointments">
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>
                                        <a href="{{ URL::to('/boss/calendar/' . $property->id . '/' . $appointment->year->year . '/' . $appointment->month_number . '/' . $appointment->day_number) }}" target="_blank">
                                            {{$appointment->date}}
                                        </a>
                                    </td>
                                    <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                                    <td>
                                        <a href="{{ URL::to('/boss/worker/appointment/list/' . $property->id . '/' . $appointment->user->id) }}">
                                            {{$appointment->user->name}} {{$appointment->user->surname}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$appointment->item->name}}
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blank">
                                            {{$appointment->employee_name}}
                                        </a>
                                    </td>
                                    <td>
                                        {{config('appointment-status.' . $appointment->status)}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center" style="padding: 1rem 1rem 0 1rem;">
                    <h3>@lang('common.no_appointments')</h3>
                    <h4>@lang('common.no_appointments_description')</h4>
                    <div style="padding: 1rem;">
                        <a href="{{ URL::to('/boss/calendar/' . $property->id . '/0/0/0') }}" class="btn pallet-1-3 btn-lg" style="color: white;">
                            @lang('common.show')
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection