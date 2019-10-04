@extends('layouts.app')

@section('content')

{!! Html::script('js/worker_show.js') !!}

<div class="container">

    <h2 class="text-center">{{ $worker->name }} {{ $worker->surname }}</h2>
    <h4 class="text-center">
        @lang('common.subscription')
        {!! $subscription->name !!}
    </h4>
    <h5 id="intervalPeriod" class="text-center">
        @lang('common.for_the_period_from')
        {{$interval->start_date->format('Y-m-d')}} 
        @lang('common.to') 
        {{$interval->end_date->format('Y-m-d')}}
    </h5>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3 style="padding: 9px;">@lang('common.description')</h3>
                <p>@lang('common.name') : <strong>{{$worker->name}} {{ $worker->surname }}</strong></p>
                <p>@lang('common.email_address') : <strong>{{$worker->email}}</strong></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="text-center">
                    @if ($substart->isActive)
                        <label for="timePeriod">@lang('common.select_a_billing_period') :</label>
                        <select id="timePeriod" class="form-control" data-substart_id="{{$substart->id}}">
                            @foreach ($substartIntervals as $substartInterval)
                                @if ($interval->start_date == $substartInterval->start_date &&
                                     $interval->end_date == $substartInterval->end_date)
                                    <option data-time_period="{{$substartInterval->start_date->format('Y-m-d')}} - {{$substartInterval->end_date->format('Y-m-d')}}" value="{{$substartInterval->id}}" selected>{{$substartInterval->start_date->format('Y-m-d')}} - {{$substartInterval->end_date->format('Y-m-d')}}</option>
                                @else
                                    <option data-time_period="{{$substartInterval->start_date->format('Y-m-d')}} - {{$substartInterval->end_date->format('Y-m-d')}}" value="{{$substartInterval->id}}">{{$substartInterval->start_date->format('Y-m-d')}} - {{$substartInterval->end_date->format('Y-m-d')}}</option>
                                @endif
                            @endforeach
                        </select>
                    @else
                        <p>@lang('common.subscription_first_time_activation_info')</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div id="appointments-table">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>                
                    <td>@lang('common.date')</td>
                    <td>@lang('common.hour')</td>
                    <td>@lang('common.massage')</td>
                    <td>@lang('common.executor')</td>
                    <td>@lang('common.status')</td>
                </tr>
            </thead>
            <tbody id="appointments">
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{$appointment->date}}</td>
                        <td>{{$appointment->start_time}} - {{$appointment->end_time}}</td>
                        <td>{{$appointment->item->name}}</td>
                        <td>
                            <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blanc">
                                {{$appointment->employee}}
                            </a>
                        </td>
                        <td>
                            {{config('appointment-status.' . $appointment->status)}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div id="workerId" data-worker_id="{{ $worker->id }}"></div>
</div>
@endsection