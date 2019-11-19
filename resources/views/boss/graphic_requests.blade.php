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
                            <td>{{$graphicRequest->day->day_number}} {{$graphicRequest->month->month}} {{$graphicRequest->year->year}}</td>
                            <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                            <td>{{$graphicRequest->comment}}</td>
                            <td>{{count($graphicRequest->employees)}}</td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/boss/graphic-request/' . $graphicRequest->id . '/0') }}">
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
            <h1 style="padding-bottom: 1rem;">@lang('common.graphic_requests_header')</h1>
            @if ($property !== null)
                <h4>@lang('common.graphic_requests_header_description')</h4>
                <div style="padding: 1rem;">
                    <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/user/property/' . $property->id) }}">
                        @lang('common.go_to_schedule')
                    </a>
                </div>
            @else
                <h3>@lang('common.graphic_requests_header_description_2')</h3>
                <h4>@lang('common.go_to_schedule_description_4')</h4>
                <div style="padding: 1rem;">
                    <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/boss/subscription/list/0/0') }}">
                        @lang('common.subscriptions_list')
                    </a>
                </div>
            @endif
        </div> 
    @endif
</div>
@endsection