@extends('layouts.app')

@section('content')

<script src="{{ asset('js/backend_appointment_create.js') }}" defer></script>
<link rel="stylesheet" href="{{ asset('css/backend_appointment_create.css') }}">

<div class="container">

    <div class="row text-center" style="padding: 2rem 0 2rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/employee/backend-calendar/'. $property->id . '/' . $year->year . '/' . $month->month_number . '/' . $day->day_number) }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.back_to_calendar')
            </a>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>

    <div class="jumbotron">
        <h1 class="text-center">@lang('common.reserve_appointment')</h1>

        <form id="appointment-create" action="{{ action([App\Http\Controllers\WorkerController::class, 'appointmentStore']) }}" method="POST">
            @csrf

            <div class="text-center">
                <div class="form-group">
                    <p>
                        @lang('common.hour'):
                        <strong style="font-size: 20px;">{{$appointmentTerm}}</strong>
                    </p>
                    @if ($appointmentTerm)
                        <input type="hidden" name="appointmentTerm" value="{{ $appointmentTerm }}">
                    @else
                        <input type="hidden" name="appointmentTerm" value="{{ old('appointmentTerm') }}">
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
                                <ul id="result" class="list-group"></ul>
                            </div>
                            <div class="warning"></div>
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

            @if ($property->id)
                <input type="hidden" name="propertyId" value="{{ $property->id }}">
            @else
                <input type="hidden" name="propertyId" value="{{ old('propertyId') }}">
            @endif

            @if ($graphic->id)
                <input type="hidden" name="graphicId" value="{{ $graphic->id }}">
            @else
                <input type="hidden" name="graphicId" value="{{ old('graphicId') }}">
            @endif

            @if ($year)
                <input type="hidden" name="yearId" value="{{ $year->id }}">
            @else
                <input type="hidden" name="yearId" value="{{ old('yearId') }}">
            @endif

            @if ($month)
                <input type="hidden" name="monthId" value="{{ $month->id }}">
            @else
                <input type="hidden" name="monthId" value="{{ old('monthId') }}">
            @endif

            @if ($day)
                <input type="hidden" name="dayId" value="{{ $day->id }}">
            @else
                <input type="hidden" name="dayId" value="{{ old('dayId') }}">
            @endif

            @if ($possibleAppointmentLengthInMinutes)
                <input type="hidden" name="possibleAppointmentLengthInMinutes" value="{{ $possibleAppointmentLengthInMinutes }}">
            @else
                <input type="hidden" name="possibleAppointmentLengthInMinutes" value="{{ old('possibleAppointmentLengthInMinutes') }}">
            @endif

            <div class="text-center">
                <input type="submit" value="@lang('common.reserve')" class="btn pallet-1-3" style="color: white;">
            </div>
        </form>

    </div>
</div>
@endsection
