@extends('layouts.app')

@section('content')

{!! Html::style('css/backend_appointment_show.css') !!}
{!! Html::script('js/backend_appointment_show.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding: 3rem 0 1rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/employee/backend-calendar/'. $property->id . '/' . $year . '/' . $month->month_number . '/' . $day) }}" class="btn pallet-1-2" style="color: white;">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/employee/backend-appointment/index/' . $appointment->user->id) }}">
                @lang('common.current_user_appointments')
            </a>
        </div>
        <div class="col-4">
            <a class="btn pallet-2-2 delete" style="color: white; margin: 2px;" data-appointment_id="{{$appointment->id}}">
                @lang('common.remove_appointment')
            </a>
            <a class="btn pallet-1-4" style="color:white; margin: 2px;" href="{{ URL::to('/employee/backend-appointment/edit/' . $appointment->id) }}">
                @lang('common.appointment_edit')
            </a>
        </div>
    </div>
    
    <div class="row text-center" style="padding: 2rem;">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron">
                <div class="text-center" style="margin-bottom: 18px;">
                    <h1 style="padding-bottom: 1rem;">@lang('common.appointment')</h1>
                    <h3><strong>{{$appointment->user->name}} {{$appointment->user->surname}}</strong></h3>
                    <h3>@lang('common.in') {{$property->name}}</h3>
                </div>
                <p>@lang('common.date'): <strong>{{$day}} {{$month->month}} {{$year}}</strong></p>
                <p>@lang('common.hour'): <strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
                <p>@lang('common.time'): <strong>{{$appointment->minutes}} @lang('common.minutes')</strong></p>
                <p>@lang('common.massage'): <strong>{{$appointment->item->name}}</strong></p>
                <p>@lang('common.description'): <strong>{{$appointment->item->description}}</strong></p>
                <p>@lang('common.price'): <strong>{{$appointment->item->price}} zł</strong></p>
                <p>
                    @lang('common.executor'): 
                    <strong>
                        <a href="{{ URL::to('/employee/' . $employee->slug) }}">
                            {{$employee->name}} {{$employee->surname}}
                        </a>
                    </strong>
                </p>
                <p>
                    @lang('common.status'): <strong id="status">{{config('appointment-status.' . $appointment->status)}}</strong>
                </p>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <select id="appointment-status" class="form-control">
                            @foreach ($statuses as $status)
                                @if ($status['isActive'])
                                    <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}" selected="selected">{{$status['value']}}</option>
                                @else
                                    <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}">{{$status['value']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
    
    <div id="deleteAppointment" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.appointment_delete')</h4>
                <button id="deleteAppointmentCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn pallet-2-2" style="color: white;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
</div>
@endsection