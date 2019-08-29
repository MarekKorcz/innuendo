@extends('layouts.app')

@section('content')

{!! Html::script('js/backend_appointment_show.js') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <a href="{{ URL::to('/employee/backend-calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-success">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li>
                {!!Form::open(['action' => ['UserController@appointmentDestroy', $appointment->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    <input type="submit" value="@lang('common.cancel_the_visit')" class="btn btn-danger">
                {!!Form::close()!!}
                <a class="btn btn-dark" href="{{ URL::to('/employee/backend-appointment/edit/' . $appointment->id) }}">
                    @lang('common.appointment_edit')
                </a>
                @if ($appointment->user)
                    <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/index/' . $appointment->user->id) }}">
                @else
                    <a class="btn btn-primary" href="{{ URL::to('/employee/backend-appointment/index/temp-user/' . $appointment->tempUser->id) }}">
                @endif
                    @lang('common.current_user_massages')
                </a>
            </li>
        </ul>
    </nav>

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.massage_in') :<strong>{{$property->name}}</strong></h2>
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
</div>
@endsection