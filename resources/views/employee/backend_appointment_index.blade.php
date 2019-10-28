@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding: 2rem 0 1rem 0;">
        <div class="col-4">
            <a class="btn pallet-1-3" style="color: white;" href="{{ URL::previous() }}">
                @lang('common.go_back')
            </a>
        </div>
        <div class="col-8"></div>
    </div>

    <div class="text-center" style="padding: 1rem;">
        <h2>
            @lang('common.all_appointments') 
            <strong>
                {{$user->name}} {{$user->surname}}
            </strong>
        </h2>
    </div>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            @if (count($appointments) > 0)
                <div id="table" style="padding: 1rem 1rem 2rem 1rem;">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.date')</td>
                                <td>@lang('common.hour')</td>
                                <td>@lang('common.where')</td>
                                <td>@lang('common.label')</td>
                                <td>@lang('common.time')</td>
                                <td>@lang('common.executor')</td>
                                <td>@lang('common.status')</td>
                                <td>@lang('common.action')</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                <tr>
                                    <td>{{$appointment->date}}</td>
                                    <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                                    <td>{{$appointment->name}}</td>
                                    <td>{{$appointment->item->name}}</td>
                                    <td>{{$appointment->minutes}}</td>
                                    <td>
                                        <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}">
                                            {{$appointment->employee}}
                                        </a>
                                    </td>
                                    <td>
                                        {{config('appointment-status.' . $appointment->status)}}
                                    </td>
                                    <td>
                                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/employee/backend-appointment/show/' . $appointment->id) }}">
                                            @lang('common.show')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-5"></div>
                        <div class="col-2 text-center">
                            {{ $appointments->links() }}
                        </div>
                        <div class="col-5"></div>
                    </div>
                </div>
            @else
                <div class="text-center" style="padding: 0 0 2rem 0;">
                    <h3>
                        {{$user->name}} {{$user->surname}}
                        @lang('common.has_no_appointments')
                    </h3>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection