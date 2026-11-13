@extends('layouts.app')
@section('content')

<script src="{{ asset('js/graphic_request.js') }}" defer></script>

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        <h1 class="text-center">@lang('common.edit_graphic_request')</h1>

        <form id="request-form" action="{{ action([\App\Http\Controllers\BossController::class, 'graphicRequestUpdate']) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="start_time">@lang('common.start_time')</label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $graphicRequest->start_time) }}" class="form-control">
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="end_time">@lang('common.end_time')</label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $graphicRequest->end_time) }}" class="form-control">
                <div class="warning"></div>
            </div>

            <div id="appointment-quantity-counter"></div>

            <p class="text-center">@lang('common.choose_from_within_our_employees')</p>
            <ul id="employees" style="padding-left: 12px; padding-right: 12px;">
                @foreach($graphicRequest->allEmployees as $employee)
                    @if ($employee->isChosen == true)
                        <li class="form-control" style="background-color: lightgreen;" data-active="true" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                    @else
                        <li class="form-control" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                    @endif
                @endforeach
            </ul>
            <div id="employees-warning" class="warning"></div>

            <div class="form-group">
                <label for="comment">@lang('common.comment')</label>
                <textarea id="comment" name="comment" class="form-control">{{ old('comment', $graphicRequest->comment) }}</textarea>
            </div>

            @foreach($graphicRequest->allEmployees as $employee)
                @if ($employee->isChosen == true)
                    <input type="hidden" name="employees[]" value="{{$employee->id}}"/>
                @endif
            @endforeach

            <input type="hidden" name="graphic_request_id" value="{{ $graphicRequest->id }}">

            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>
        </form>
    </div>
</div>
@endsection
