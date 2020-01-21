@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4"></div>
        <div class="col-4">
            @if ($graphicRequest)
                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('admin/graphic-request/' . $graphicRequest->id) }}">
                    @lang('common.graphic_request')
                </a>
            @endif
        </div>
        <div class="col-4">
            @if ($month)
                <a class="btn btn-success" href="{{ URL::to('month/show/' . $month->id) }}">
                    @lang('common.back_to_month')
                </a>
            @endif
        </div>
    </div>

    <div style="padding: 20px;" class="text-center">
        <h2><strong>{{$day->day_number}} {{$month->month}} {{$year->year}}</strong></h2>
        <h3>{{$property->name}}</h3>
        <h5>{{$property->street}} {{$property->street_number}} / {{$property->house_number}}, {{$property->city}}</h5>
    </div>
    
    <div class="row" style="padding-bottom: 2rem;">
        <div class="offset-1"></div>
        <div class="col-10">
            @if (count($graphics) > 0)
                <table class="table table-striped table-bordered text-center">
                    <thead>
                        <tr>                
                            <td>@lang('common.hours')</td>
                            <td>@lang('common.minutes_capital')</td>
                            <td>@lang('common.employee')</td>
                            <td>@lang('common.action')</td>
                        </tr>
                    </thead>
                    <tbody>   
                        @foreach ($graphics as $graphic)
                            <tr>
                                <td>{{$graphic->start_time}} - {{$graphic->end_time}}</td>
                                <td>{{$graphic->total_time}}</td>
                                <td>
                                    <a href="{{ URL::to('/employee/' . $graphic->employee->slug) }}">
                                        {{$graphic->employee->name}} {{$graphic->employee->surname}}
                                    </a>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="{{ URL::to('employee/backend-calendar/' . $property->id . '/' . $year->year . '/' . $month->month_number . '/' . $day->day_number) }}">
                                        @lang('common.show')
                                    </a>
                                    <a class="btn btn-primary" href="{{ URL::to('graphic/' . $graphic->id . '/edit') }}">
                                        @lang('common.edit')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <div class="text-center" style="padding-bottom: 2rem;">
                <a class="btn btn-success" href="{{ action('GraphicController@create', $day->id) }}">
                    @lang('common.create_graphic')
                </a>
            </div>
        </div>
        <div class="offset-1"></div>
    </div>
</div>
@endsection