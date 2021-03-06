@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 1rem;">

        <div class="text-center">
            <h1>@lang('common.your_appointments')</h1>
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
                @if (count($appointments) > 0)
                    @foreach($appointments as $appointment)
                        <tr>
                            <td>
                                @if ($user->isBoss)
                                    <a href="{{ URL::to('boss/calendar/' . $appointment['property']->id . '/' . $appointment['year'] . '/' . $appointment['month_number'] . '/' . $appointment['day_number']) }}">
                                @else
                                    <a href="{{ URL::to('user/calendar/' . $appointment['property']->id . '/' . $appointment['year'] . '/' . $appointment['month_number'] . '/' . $appointment['day_number']) }}">
                                @endif
                                        {{$appointment->date}}
                                    </a>
                            </td>
                            <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                            <td>{{$appointment->address}}</td>
                            <td>
                                {{$appointment->item->name}}
                            </td>
                            <td>{{$appointment->minutes}}</td>
                            <td>
                                <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blank">
                                    {{$appointment->employee_name}}
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
                @endif
                </tbody>
            </table>
        @else
            <div class="text-center" style="padding: 1rem;">
                <h3>@lang('common.go_to_schedule_description_1')</h3>
                <h4>@lang('common.go_to_schedule_description_2')</h4>
                <div style="padding: 1rem;">
                    <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/user/properties') }}">
                        @lang('common.go_to_schedule')
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection