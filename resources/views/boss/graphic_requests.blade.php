@extends('layouts.app')
@section('content')
<div class="container">

    @if (count($graphicRequests) > 0)
        <div style="padding: 1rem;">
            <div class="text-center" style="padding: 1rem;">
                <h1>@lang('common.all_graphic_requests')</h1>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.date')</td>
                        <td>@lang('common.hour')</td>
                        <td>@lang('common.comment')</td>
                        <td>@lang('common.number_of_employees')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graphicRequests as $graphicRequest)
                        <tr>
                            <td>{{$graphicRequest->day->day_number}} {{$graphicRequest->day->month->month}} {{$graphicRequest->day->month->year->year}}</td>
                            <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                            <td>{{$graphicRequest->comment}}</td>
                            <td>{{count($graphicRequest->employees)}}</td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/boss/graphic-request/' . $graphicRequest->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center" style="padding: 1rem;">
            <h3>@lang('common.send_graphic_request')</h3>
            <h4>@lang('common.go_to_schedule_description_2')</h4>
            <div style="padding: 1rem;">
                <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/user/properties') }}">
                    @lang('common.go_to_schedule')
                </a>
            </div>
        </div>
    @endif
</div>
@endsection