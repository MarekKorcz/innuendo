@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 2rem 0 2rem 0;">
        
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.all_graphic_requests')</h1>

        @if (count($graphicRequests) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.owner')</td>
                        <td>@lang('common.date')</td>
                        <td>@lang('common.time')</td>
                        <td>@lang('common.comment')</td>
                        <td>@lang('common.employees')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($graphicRequests as $graphicRequest)
                        <tr>
                            <td>{{$graphicRequest->property->name}} - {{$graphicRequest->boss->name}} {{$graphicRequest->boss->surname}}</td>
                            <td>{{$graphicRequest->day->month->year->year}} {{$graphicRequest->day->month->month}} {{$graphicRequest->day->day_number}}</td>
                            <td>{{$graphicRequest->start_time}} - {{$graphicRequest->end_time}}</td>
                            <td>{{$graphicRequest->comment}}</td>
                            <td>{{count($graphicRequest->employees)}}</td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/admin/graphic-request/' . $graphicRequest->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_graphic_requests_description')</h3>
            </div>
        @endif
    </div>
</div>
@endsection