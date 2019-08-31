@extends('layouts.app')
@section('content')
<div class="container">

    @if (count($graphicRequests) > 0)
        <div style="padding: 1rem;">
            <div class="text-center">
                <h2>@lang('common.all_graphic_requests')</h2>
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
                            <td>{{$graphicRequest->year->year}} {{$graphicRequest->month->month}} {{$graphicRequest->day->day_number}}</td>
                            <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                            <td>{{$graphicRequest->comment}}</td>
                            <td>{{count($graphicRequest->employees)}}</td>
                            <td>
                                <a class="btn btn-success" href="{{ URL::to('/boss/graphic-request/' . $graphicRequest->id . '/0') }}">
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
            <h1>@lang('common.graphic_requests_header')</h1>
            @if ($property !== null)
                <h3>@lang('common.graphic_requests_header_description')</h3>
                <a class="btn btn-success" href="{{ URL::to('/user/property/' . $property->id) }}">
                    @lang('common.go_to_schedule')
                </a>
            @else
                <h3>@lang('common.graphic_requests_header_description_2')</h3>
                <h4>@lang('common.go_to_schedule_description_4')</h4>
                <a class="btn btn-success" href="{{ URL::to('/boss/subscription/list/0/0') }}">
                    @lang('common.subscriptions_list')
                </a>
            @endif
        </div> 
    @endif
</div>
@endsection