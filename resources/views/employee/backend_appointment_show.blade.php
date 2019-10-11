@extends('layouts.app')

@section('content')

{!! Html::style('css/backend_appointment_show.css') !!}
{!! Html::script('js/backend_appointment_show.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding: 2rem;">
        <div class="col-4">
            <a href="{{ URL::to('/employee/backend-calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-success">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <div class="col-4">
            @if ($appointment->user)
                <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/index/' . $appointment->user->id) }}">
            @else
                <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/index/temp-user/' . $appointment->tempUser->id) }}">
            @endif
                @lang('common.current_user_massages')
            </a>
        </div>
        <div class="col-4">
            <a class="btn btn-danger delete" style="color: white;" data-appointment_id="{{$appointment->id}}">
                @lang('common.remove_appointment')
            </a>
            <a class="btn btn-dark" href="{{ URL::to('/employee/backend-appointment/edit/' . $appointment->id) }}">
                @lang('common.appointment_edit')
            </a>
        </div>
    </div>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.massage_in'): <strong>{{$property->name}}</strong></h2>
        </div>
        <p>@lang('common.date') : <strong>{{$day}} {{$month}} {{$year}}</strong></p>
        <p>@lang('common.hour') : <strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
        <p>@lang('common.time') : <strong>{{$appointment->minutes}} @lang('common.minutes')</strong></p>
        <p>@lang('common.massage') : <strong>{{$appointment->item->name}}</strong></p>
        <p>@lang('common.description') : <strong>{{$appointment->item->description}}</strong></p>
        <p>@lang('common.price') : <strong>{{$appointment->item->price}} zł</strong></p>
        <p>@lang('common.executor') : <strong>{{$employee->name}}</strong></p>
        <p>
            @lang('common.status') : <strong id="status">{{config('appointment-status.' . $appointment->status)}}</strong>
        </p>
        @if ($isActivated)
            <select id="appointment-status" class="form-control">
                @foreach ($statuses as $status)
                    @if ($status['isActive'])
                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}" selected="selected">{{$status['value']}}</option>
                    @else
                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}">{{$status['value']}}</option>
                    @endif
                @endforeach
            </select>
        @else
            <a class="btn btn-primary" href="{{ URL::to('/employee/activate-subscription/' . $subscriptionPurchaseId . '/' . $appointment->id) }}">
                @lang('common.activate_subscription')
            </a>
        @endif
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
                        <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
</div>
@endsection