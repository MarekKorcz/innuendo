@extends('layouts.app')
@section('content')
<div class="container">

<!--    <nav class="navbar navbar-inverse">
        <div class="navbar-header" style="padding-top: 1rem;">
            <a class="btn btn-success" href="{{ URL::previous() }}">
                @lang('common.go_back')
            </a>
        </div>
    </nav>-->

    <div style="padding: 1rem;">

        <div class="text-center">
            <h1>@lang('common.all_massages')</h1>
        </div>

        @if (count($appointments) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.date')</td>
                        <td>@lang('common.hour')</td>
                        <td>@lang('common.address')</td>
                        <td>@lang('common.label')</td>
                        <td>@lang('common.time')</td>
                        <td>@lang('common.executor')</td>
                        <td>@lang('common.status')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                @if ($user->isBoss)
                                    <a href="{{ URL::to('boss/calendar/' . $appointment['calendar']->id . '/' . $appointment['year'] . '/' . $appointment['month'] . '/' . $appointment['day']) }}">
                                @else
                                    <a href="{{ URL::to('user/calendar/' . $appointment['calendar']->id . '/' . $appointment['year'] . '/' . $appointment['month'] . '/' . $appointment['day']) }}">
                                @endif
                                        {{$appointment->date}}
                                    </a>
                            </td>
                            <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                            <td>{{$appointment->address}}</td>
                            <td>
                                @if ($user->isBoss)
                                    <a href="{{ URL::to('/boss/subscription/list/' . $appointment->substart->property_id . '/' . $appointment->substart->subscription_id) }}" target="_blanc">  
                                @else
                                    <a href="{{ URL::to('/user/subscription/list/' . $appointment->substart->id) }}" target="_blanc">                     
                                @endif
                                        {{$appointment->item->name}}
                                    </a>
                            </td>
                            <td>{{$appointment->minutes}}</td>
                            <td>
                                <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blanc">
                                    {{$appointment->employee}}
                                </a>
                            </td>
                            <td>
                                {{config('appointment-status.' . $appointment->status)}}
                            </td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/appointment/show/' . $appointment->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center" style="padding: 1rem;">
                @if ($property !== null)
                    <h3>@lang('common.go_to_schedule_description_1')</h3>
                    <h4>@lang('common.go_to_schedule_description_2')</h4>
                    <div style="padding: 1rem;">
                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/user/property/' . $property->id) }}">
                            @lang('common.go_to_schedule')
                        </a>
                    </div>
                @else
                    @if ($user->isBoss == 1)
                        <h3>@lang('common.go_to_schedule_description_3')</h3>
                        <h4>@lang('common.go_to_schedule_description_4')</h4>
                        <div style="padding: 1rem;">
                            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/boss/subscription/list/0/0') }}">
                                @lang('common.subscriptions_list')
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
</div>
@endsection