@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}
{!! Html::script('js/worker_appointment_list.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ url()->previous() }}">
                @lang('common.go_back')
            </a>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
    
    <div class="row" style="padding: 1rem 0 1rem 0;">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="text-center" style="padding-top: 2rem;">
                <h2>
                    @lang('common.subscription_massages')
                    {!! $subscription->name !!}
                </h2>
            </div>
            @if (count($appointments) > 0)
                <div id="workers-panel" class="wrapper cont">
                    @if ($substart->isActive)
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
                        </div>
                    @endif
                </div>
            
                <div id="appointments-table">
                    @if ($substart->isActive == 0)
                        <div class="col-12">
                            <h2 class="text-center">
                                @lang('common.items')
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
                    @endif
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
                                        <!--<a href="{{ URL::to('/boss/worker/show/' . $appointment->user->id . '/' . $substart->id . '/' . $appointment->interval_id) }}" target="_blanc">-->
                                            {{$appointment->user->name}} {{$appointment->user->surname}}
                                        <!--</a>-->
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
                </div>
            @else
                <div class="text-center" style="padding: 1rem 1rem 0 1rem;">
                    <h3>@lang('common.subscription_first_time_activation_info')</h3>
                    <div style="padding: 1rem;">
                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/appointment/index') }}">
                            @lang('common.go_to_appointments')
                        </a>
                    </div>
                </div>
                </hr>
                <div class="text-center" style="padding: 1rem;">
                    <h3>
                        @lang('common.go_to_codes_view_description_2')
                    </h3>
                    <div style="padding: 1rem;">
                        <a class="btn pallet-2-1" style="color: white;" href="{{ URL::to('/boss/codes') }}">
                            @lang('common.register_codes')
                        </a>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection