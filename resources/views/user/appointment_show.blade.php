@extends('layouts.app')
@section('content')

{!! Html::style('css/appointment_show.css') !!}

<div class="container">
    
    <div class="row text-center" style="padding: 2rem;">
        <div class="col-4">
            @if ($user->isBoss)
                <a href="{{ URL::to('/boss/calendar/'. $property->id . '/' . $year . '/' . $month_number . '/' . $day) }}" class="btn pallet-1-3" style="color: white;">
            @elseif (!$user->isAdmin && !$user->isEmployee)   
                <a href="{{ URL::to('/user/calendar/'. $property->id . '/' . $year . '/' . $month_number . '/' . $day) }}" class="btn pallet-1-3" style="color: white;">
            @endif
                @lang('common.back_to_calendar')
            </a>
        </div>
        <div class="col-4">
            <a class="btn btn-primary" href="{{ URL::to('/appointment/index') }}">
                @lang('common.all_massages')
            </a>
        </div>
        <div class="col-4">
            @if ($canBeDeleted && $appointment->status !== 1)
                <a href="#deleteAnAppointment" data-toggle="modal" class="btn btn-danger">
                    @lang('common.remove_appointment')
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron">
                <div class="text-center" style="margin-bottom: 40px;">
                    <h2>@lang('common.massage_in'): <strong>{{$property->name}}</strong></h2>
                </div>
                <p>@lang('common.date'): <strong>{{$day}} {{$month}} {{$year}}</strong></p>
                <p>@lang('common.hour'): <strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
                <p>@lang('common.address'): <strong>{{$property->street}} {{$property->street_number}} {{$property->house_number}}, {{$property->city}}</strong></p>
                <p>@lang('common.time'): <strong>{{$appointment->minutes}} @lang('common.minutes')</strong></p>
                <p>@lang('common.massage'): <strong>{{$appointment->item->name}}</strong></p>
                <p>@lang('common.description'): <strong>{{$appointment->item->description}}</strong></p>
                <p>@lang('common.price'): <strong>{{$appointment->item->price}} zł</strong></p>
                <p>
                    @lang('common.executor'): 
                    <a href="{{ URL::to('employee/' . $employee->slug) }}">
                        <strong>{{$employee->name}} {{$employee->surname}}</strong>
                    </a>
                </p>
                <p>
                    @lang('common.status'): <strong>{{config('appointment-status.' . $appointment->status)}}</strong>
                </p>
            </div>

            <div class="modal hide" id="deleteAnAppointment">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>@lang('common.appointment_removal')</h3>
                        <button class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <a class="btn btn-success" data-dismiss="modal" href="#">@lang('common.go_back')</a>
                            </div>
                            <div class="col-6" style="text-align: right;">
                                {!!Form::open(['action' => ['UserController@appointmentDestroy', $appointment->id], 'method' => 'POST'])!!}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    <input type="submit" value="@lang('common.cancel_the_visit')" class="btn btn-primary">
                                {!!Form::close()!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection