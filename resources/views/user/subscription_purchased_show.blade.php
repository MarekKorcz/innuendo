@extends('layouts.app')
@section('content')

{!! Html::style('css/worker_appointment_list.css') !!}
{!! Html::script('js/subscription_purchased_show.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding-top: 1rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('user/subscription/list/' . $purchase->substart_id) }}">
                @lang('common.subscriptions')
            </a>
        </div>
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
                <div id="user-panel" class="wrapper cont">
                    @if ($substart->isActive)
                        <div class="text-center">
                            <label for="timePeriod">@lang('common.select_a_billing_period'):</label>
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
                            <input id="search" class="form-control" type="hidden" value="{{$user->name . " " . $user->surname}}" data-user_id="{{$user->id}}" autocomplete="off">
                        </div>
                    @endif
                </div>
            
                <div id="appointments-table">
                    @if ($substart->isActive == 0)
                        <div class="col-12">
                            <h2 class="text-center">
                                @lang('common.items')
                                @if ($user !== null)
                                    @lang('common.belonging_to')                
                                    {{$user->name}} {{$user->surname}}
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
                                    <td>
                                        <a href="{{ URL::to('/user/calendar/' . $appointment->calendar_id . '/' . $appointment->year . '/' . $appointment->month . '/' . $appointment->day) }}" target="_blank">
                                            {{$appointment->date}}
                                        </a>
                                    </td>
                                    <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                                    <td>
                                        {{$appointment->user->name}} {{$appointment->user->surname}}
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/user/subscription/list/' . $substart->id) }}" target="_blank">
                                            {{$appointment->item->name}}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blank">
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