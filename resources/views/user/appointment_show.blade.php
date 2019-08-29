@extends('layouts.app')
@section('content')

{!! Html::style('css/appointment_show.css') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="{{ URL::to('/employee/calendar/'. $calendarId . '/' . $year . '/' . $month_number . '/' . $day) }}" class="btn btn-success">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <ul class="nav navbar-nav">
            @if ($canBeDeleted)
                <li>                
                    <a href="#deleteAnAppointment" data-toggle="modal" class="btn btn-danger">
                        @lang('common.remove_appointment')
                    </a>
                </li>
            @endif
            <li>
                <a class="btn btn-primary" href="{{ URL::to('/appointment/index') }}">
                    @lang('common.all_massages')
                </a>
            </li>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.massage_in') :<strong>{{$property->name}}</strong></h2>
        </div>
        <p>@lang('common.date') :<strong>{{$day}} {{$month}} {{$year}}</strong></p>
        <p>@lang('common.hour') :<strong>{{$appointment->start_time}}</strong> - <strong>{{$appointment->end_time}}</strong></p>
        <p>@lang('common.address') :<strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}</strong></p>
        <p>@lang('common.time') :<strong>{{$appointment->minutes}} @lang('common.minutes')</strong></p>
        <p>@lang('common.massage') :<strong>{{$appointment->item->name}}</strong></p>
        <p>@lang('common.description') :<strong>{{$appointment->item->description}}</strong></p>
        <p>@lang('common.price') :<strong>{{$appointment->item->price}} zł</strong></p>
        <p>
            @lang('common.executor') : 
            <a href="{{ URL::to('employee/' . $employee->slug) }}">
                <strong>{{$employee->name}} {{$employee->surname}}</strong>
            </a>
        </p>
        <p>
            @lang('common.status') : <strong>{{config('appointment-status.' . $appointment->status)}}</strong>
        </p>
        @if ($subscription)
            <p>
                @lang('common.subscription_capital') : 
                <a href="{{ URL::to('user/subscription/purchased/show/' . $appointment->purchase->id) }}">
                    <strong>{{$subscription->name}}</strong>
                </a>
            </p>
        @endif
    </div>
    
    <div class="modal hide" id="deleteAnAppointment">
        <div class="modal-content">
            <div class="modal-header">
                <h3>@lang('common.appointment_removal')</h3>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <p>@lang('common.appointment_removal_description')</p>
                
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
@endsection