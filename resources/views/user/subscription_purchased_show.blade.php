@extends('layouts.app')

@section('content')

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{$purchase->subscription->name}}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>@lang('common.label') : <strong>{{$purchase->subscription->name}}</strong></p>
                <p>{{$purchase->subscription->description}}</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h2>
                    @lang('common.status')
                </h2>
                @if ($expirationDate !== null)
                    @if ($intervalAvailableUnits !== null)
                        <p>
                            @lang('common.available_massages_in_current_month')
                            {{$substartInterval->start_date->format('Y-m-d')}} 
                            @lang('common.to') 
                            {{$substartInterval->end_date->format('Y-m-d')}} 
                            ):
                            <strong>
                                {{$intervalAvailableUnits}}
                            </strong>
                        </p>
                    @endif
                    <p>
                        @lang('common.valid_until') : 
                        <strong>
                            {{$expirationDate}}
                        </strong>
                    </p>
                @else
                    <p>@lang('common.subscription_first_time_activation_info')</p>
                @endif
            </div>
        </div>
    </div>
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                <h2 class="text-center">@lang('common.appointments_list')</h2>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>                
                            <td>@lang('common.date')</td>
                            <td>@lang('common.hour')</td>
                            <td>@lang('common.address')</td>
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
                                <td>{{$appointment->address}}</td>
                                <td>{{$appointment->item->name}}</td>
                                <td>{{$appointment->minutes}}</td>
                                <td>
                                    <a href="{{ URL::to('/employee/' . $appointment->employee_slug) }}" target="_blanc">
                                        {{$appointment->employee}}
                                    </a>
                                </td>
                                <td>
                                    {{config('appointment-status.' . $appointment->status)}}
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{ URL::to('/appointment/show/' . $appointment->id) }}">
                                        @lang('common.show')
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection