@extends('layouts.app')
@section('content')

{!! Html::script('js/subscription_create.js') !!}

<div class="container">

    <div style="padding-top: 1rem;">
        <nav class="navbar navbar-inverse">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ URL::to('/employee/calendar/'. $calendarId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn pallet-1-3" style="color: white;">
                        @lang('common.back_to_calendar')
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    
    <div class="container" style="padding: 1rem 0 1rem 0;">
        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <h1 class="text-center">@lang('common.reserve_appointment')</h1>

                {{ Form::open(['id' => 'appointment-create', 'action' => 'AppointmentController@store', 'method' => 'POST']) }}

                    <div class="form-group">
                        <p class="text-center" style="font-size: 20px;">
                            @lang('common.hour'): 
                            <strong style="font-size: 27px;">{{$appointmentTerm}}</strong>
                        </p>
                        @if ($appointmentTerm)
                            {{ Form::hidden('appointmentTerm', $appointmentTerm) }}
                        @else
                            {{ Form::hidden('appointmentTerm', Input::old('appointmentTerm')) }}
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="text-center" style="font-size: 20px;">
                            <label for="item">@lang('common.massage'):</label>
                        </div>
                        <select id="item" name="item" class="form-control">
                            <option disabled selected value> @lang('common.choose_massage') </option>
                            @foreach ($items as $item)
                                @if ($item->subscription_name)
                                    <option value="{{$item->id}}" data-subscription_id="{{$item->subscription_id}}">{{$item->name}} - {{$item->minutes}} @lang('common.minutes') - {{$item->price}} zł - {{$item->subscription_name}}</option>
                                @else
                                    <option value="{{$item->id}}">{{$item->name}} - {{$item->minutes}} @lang('common.minutes') - {{$item->price}} zł</option>
                                @endif
                            @endforeach
                        </select>
                        <div id="item-warning"></div>
                    </div>

                    <div id="subscription"></div>        

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

                    <div class="text-center">
                        <input type="submit" value="@lang('common.reserve')" class="btn pallet-2-1" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection