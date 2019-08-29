@extends('layouts.app')
@section('content')
<div class="container">

    <h1>@lang('common.all_graphic_requests')</h1>
    
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
@endsection