@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}
{!! Html::script('js/worker_appointment_list.js') !!}

<div class="container">
    
    <div class="text-center" style="padding-top: 2rem;">
        <h2>
            @lang('common.subscription_massages')
            {!! $subscription->name !!}
        </h2>
    </div>
    @if (count($appointments) > 0)
        <div id="workers-panel" class="wrapper cont">
            <div class="text-center">
                <label for="search">@lang('common.write_your_name_and_lastname') :</label>
                @if($worker !== null)
                    <input id="search" class="form-control" type="text" value="{{$worker->name . " " . $worker->surname}}" autocomplete="off">
                @else
                    <input id="search" class="form-control" type="text" value="" autocomplete="off">          
                @endif
                <ul id="result" class="list-group"></ul>
            </div>
            <div class="text-center">
                @if ($substart->isActive)
                    <label for="timePeriod">@lang('common.select_a_billing_period') :</label>
                    <select id="timePeriod" class="form-control" data-substart_id="{{$substart->id}}">
                        @foreach ($intervals as $key => $interval)
                            @if ($interval->start_date <= $today && $interval->end_date >= $today ||
                                 $interval->end_date < $today && $key + 1 == count($intervals))
                                <option value="{{$interval->id}}" selected>{{$interval->start_date->format('Y-m-d')}} - {{$interval->end_date->format('Y-m-d')}}</option>
                            @else
                                <option value="{{$interval->id}}">{{$interval->start_date->format('Y-m-d')}} - {{$interval->end_date->format('Y-m-d')}}</option>
                            @endif
                        @endforeach
                    </select>
                @else
                    <h4>@lang('common.subscription_first_time_activation_info')</h4>
                @endif
            </div>
        </div>
    @else
        <div class="text-center" style="padding: 1rem;">
            <h3>@lang('common.subscription_first_time_activation_info')</h3>
        </div>
    @endif
    
    <hr>
    
    <div class="col-sm-12 col-md-12 col-lg-12 col-12">
        <h2 class="text-center">
            @lang('common.all_massages')
            @if ($worker !== null)
                @lang('common.belonging_to')                
                {{$worker->name}} {{$worker->surname}}
            @endif
            @if ($intervals)
                @foreach ($intervals as $interval)
                    @if ($interval->start_date <= $today && $interval->end_date >= $today)
                        @lang('common.for_the_period_from')
                        {{$interval->start_date->format('Y-m-d')}}
                        @lang('common.to')
                        {{$interval->end_date->format('Y-m-d')}}
                    @endif
                @endforeach
            @endif
        </h2>
    </div>
    
    <div id="appointments-table">
        @if (count($appointments) > 0)
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
                            <td>{{$appointment->date}}</td>
                            <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                            <td>
                                <a href="{{ URL::to('/boss/worker/show/' . $appointment->user->id . '/' . $substart->id . '/' . $appointment->interval_id) }}" target="_blanc">
                                    {{$appointment->user->name}} {{$appointment->user->surname}}
                                </a>
                            </td>
                            <td>{{$appointment->item->name}}</td>
                            <td>
                                <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blanc">
                                    {{$appointment->employee}}
                                </a>
                            </td>
                            <td>
                                {{config('appointment-status.' . $appointment->status)}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @if ($property !== null)
                <div class="text-center" style="padding: 1rem 0 1rem 0;">
                    <h3>@lang('common.no_message_appointment')</h3>
                    <h4>@lang('common.go_to_schedule_description_2')</h4>
                    <div style="padding: 1rem;">
                        <a class="btn btn-success" href="{{ URL::to('/user/property/' . $property->id) }}">
                            @lang('common.go_to_schedule')
                        </a>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection