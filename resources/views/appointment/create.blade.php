@extends('layouts.app')
@section('content')

<div class="container">

    <div style="padding-top: 1rem;">
        <nav class="navbar navbar-inverse">
            <ul class="nav navbar-nav">
                <li>
                    @if (auth()->user()->isBoss)
                        <a href="{{ URL::to('/boss/calendar/'. $propertyId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn pallet-1-3" style="color: white;">
                    @else
                        <a href="{{ URL::to('/user/calendar/'. $propertyId . '/' . $year . '/' . $month . '/' . $day) }}" class="btn pallet-1-3" style="color: white;">
                    @endif
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

                <form id="appointment-create" action="{{ action([\App\Http\Controllers\AppointmentController::class, 'store']) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <p class="text-center" style="font-size: 20px;">
                            @lang('common.hour'):
                            <strong style="font-size: 27px;">{{$appointmentTerm}}</strong>
                        </p>
                        @if ($appointmentTerm)
                            <input type="hidden" name="appointmentTerm" value="{{ $appointmentTerm }}">
                        @else
                            <input type="hidden" name="appointmentTerm" value="{{ old('appointmentTerm') }}">
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="text-center" style="font-size: 20px;">
                            <label for="item">@lang('common.massage'):</label>
                        </div>
                        <select id="item" name="item" class="form-control">
                            <option disabled selected value> @lang('common.choose_massage') </option>
                            @foreach ($items as $item)
                                <option value="{{$item->id}}">{{$item->name}} - {{$item->minutes}} @lang('common.minutes') - {{$item->price}} zł</option>
                            @endforeach
                        </select>
                        <div id="item-warning"></div>
                    </div>

                    @if ($propertyId)
                        <input type="hidden" name="propertyId" value="{{ $propertyId }}">
                    @else
                        <input type="hidden" name="propertyId" value="{{ old('propertyId') }}">
                    @endif

                    @if ($graphicId)
                        <input type="hidden" name="graphicId" value="{{ $graphicId }}">
                    @else
                        <input type="hidden" name="graphicId" value="{{ old('graphicId') }}">
                    @endif

                    @if ($year)
                        <input type="hidden" name="year" value="{{ $year }}">
                    @else
                        <input type="hidden" name="year" value="{{ old('year') }}">
                    @endif

                    @if ($month)
                        <input type="hidden" name="month" value="{{ $month }}">
                    @else
                        <input type="hidden" name="month" value="{{ old('month') }}">
                    @endif

                    @if ($day)
                        <input type="hidden" name="day" value="{{ $day }}">
                    @else
                        <input type="hidden" name="day" value="{{ old('day') }}">
                    @endif

                    <div class="text-center">
                        <input type="submit" value="@lang('common.reserve')" class="btn pallet-2-1" style="color: white;">
                    </div>
                </form>

            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection
