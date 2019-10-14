@extends('layouts.app')

@section('content')

{!! Html::script('js/backend_appointment_create.js') !!}
{!! Html::style('css/backend_appointment_create.css') !!}

<div class="container">

    <div class="row text-center" style="padding: 1rem 0 1rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/employee/backend-calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn btn-primary">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <div class="col-8"></div>
    </div>
    
    <div class="jumbotron">
        <h1 class="text-center">@lang('common.reserve_appointment')</h1>

        {{ Form::open(['id' => 'appointment-create', 'action' => 'WorkerController@appointmentStore', 'method' => 'POST']) }}

            <div class="text-center">
                <div class="form-group">
                    <p>
                        @lang('common.hour'): 
                        <strong style="font-size: 20px;">{{$appointmentTerm}}</strong>
                    </p>
                    @if ($appointmentTerm)
                        {{ Form::hidden('appointmentTerm', $appointmentTerm) }}
                    @else
                        {{ Form::hidden('appointmentTerm', Input::old('appointmentTerm')) }}
                    @endif
                </div>
            </div>

            <div id="client">
                <div class="row">
                    <div class="col-1"></div>
                    <div id="credential-1" class="col-7">
                        <div class="text-center">
                            <div class="form-group">
                                <label for="search">@lang('common.client'):</label>
                                <input id="search" class="form-control" type="text" name="search" placeholder="@lang('common.look_for_client')" autocomplete="off">
                            </div>
                            <div class="warning"></div>
                            <ul id="result" class="list-group"></ul>
                            <input id="userId" type="hidden" name="userId" value="">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center" style="padding: 3px;">
                            <label for="isNew">@lang('common.is_new')</label><br>
                            <input id="isNew" type="checkbox" style="width: 30px; height: 30px;">
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>            
            </div>

            <div class="text-center" id="items"></div>

            @if ($calendarId)
                {{ Form::hidden('calendarId', $calendarId) }}
            @else
                {{ Form::hidden('calendarId', Input::old('calendarId')) }}
            @endif

            @if ($graphicId)
                {{ Form::hidden('graphicId', $graphicId) }}
            @else
                {{ Form::hidden('graphicId', Input::old('graphicId')) }}
            @endif

            @if ($year)
                {{ Form::hidden('year', $year) }}
            @else
                {{ Form::hidden('year', Input::old('year')) }}
            @endif

            @if ($month)
                {{ Form::hidden('month', $month) }}
            @else
                {{ Form::hidden('month', Input::old('month')) }}
            @endif

            @if ($day)
                {{ Form::hidden('day', $day) }}
            @else
                {{ Form::hidden('day', Input::old('day')) }}
            @endif

            @if ($possibleAppointmentLengthInMinutes)
                {{ Form::hidden('possibleAppointmentLengthInMinutes', $possibleAppointmentLengthInMinutes) }}
            @else
                {{ Form::hidden('possibleAppointmentLengthInMinutes', Input::old('possibleAppointmentLengthInMinutes')) }}
            @endif

            @if ($propertyId)
                {{ Form::hidden('propertyId', $propertyId) }}
            @else
                {{ Form::hidden('propertyId', Input::old('propertyId')) }}
            @endif

            <div class="text-center">
                <input type="submit" value="@lang('common.reserve')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection