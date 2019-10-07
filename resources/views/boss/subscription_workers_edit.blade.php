@extends('layouts.app')
@section('content')

{!! Html::style('css/subscription_workers_edit.css') !!}
{!! Html::script('js/subscription_workers_edit.js') !!}

<div class="container" style="padding: 1rem 0 1rem 0;">
    
    <div class="text-center">
        <h2>
            @lang('common.people_assigned_to_subscription') : {!! $subscription->name !!}
        </h2>
    </div>
    <div class="wrapper cont">
        <div class="text-center">
            <div class="row">
                <div class="offset-sm-3 offset-md-3 offset-lg-3"></div>
                    @if (count($substartIntervals) > 1)
                        <div class="col-6">
                            <label for="timePeriod" style="font-size: 24px;">@lang('common.select_a_billing_period') :</label>                        
                            <ul id="timePeriod" class="list-group">
                                @foreach ($substartIntervals as $substartInterval)
                                    @if ($substartInterval->workers)
                                        <a href="{{ URL::to('/boss/subscription/workers/edit/' . $substart->id . '/' . $substartInterval->id) }}" style="text-decoration: none;">
                                            <li class="list-group-item clicked">
                                                {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                            </li>
                                        </a>   
                                    @else
                                        <a href="{{ URL::to('/boss/subscription/workers/edit/' . $substart->id . '/' . $substartInterval->id) }}" style="text-decoration: none;">
                                            <li class="list-group-item">
                                                {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                            </li>
                                        </a>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                <div class="offset-sm-3 offset-md-3 offset-lg-3"></div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12 col-md-12 col-lg-12 col-12">
        <h2 class="text-center" style="padding-top: 1rem;">
            @lang('common.employees')            
            @if (count($substartIntervals) > 0)
                @foreach ($substartIntervals as $substartInterval)
                    @if ($substartInterval->workers)
                        @lang('common.asigned_from')
                        {{$substartInterval->start_date->format('Y-m-d')}} 
                        @lang('common.to')
                        {{$substartInterval->end_date->format('Y-m-d')}}
                    @endif
                @endforeach
            @endif
        </h2>
    </div>

    @foreach($substartIntervals as $substartInterval)
        @if (count($substartInterval->workers) > 0)
            <div id="workers-table">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <td class="text-center">@lang('common.turn_on')</td>
                            <td class="text-center">@lang('common.turn_off')</td>
                            <td>@lang('common.name_and_surname')</td>
                            <td>@lang('common.email_address')</td>
                        </tr>
                    </thead>
                    <tbody id="workers">
                        {{ Form::open(['id' => 'subscription-workers-update', 'action' => ['BossController@subscriptionWorkersUpdate'], 'method' => 'POST']) }}

                            <div class="form-row">
                                @if ($substart->isActive == 1)
                                    @foreach($substartIntervals as $substartInterval)
                                        @if ($substartInterval->workers)
                                            @foreach($substartInterval->workers as $worker)
                                                <tr>
                                                    @if ($today > $substartInterval->start_date && $today > $substartInterval->end_date)
                                                        @if ($worker->withoutSubscription == false)
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.had_subscription')
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        @else
                                                            <td></td>
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.did_not_have_subscription')
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @elseif ($today >= $substartInterval->start_date && $today <= $substartInterval->end_date)
                                                        @if ($worker->withoutSubscription == false)
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.have_subscription')
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        @else
                                                            <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.do_not_have_subscription')
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @else
                                                        @if ($worker->withoutSubscription == false)
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.have_subscription')
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="workers_off[]" value="{{$worker->id}}">
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.do_not_have_subscription')
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @endif
                                                    <td>{{$worker->name}} {{$worker->surname}}</td>
                                                    <td>{{$worker->email}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($substartIntervals as $substartInterval)
                                        @if ($substartInterval->workers)
                                            @foreach($substartInterval->workers as $worker)
                                                <tr>
                                                    @if ($today >= $substartInterval->start_date && $today <= $substartInterval->end_date)
                                                        @if ($worker->withoutSubscription == false)
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.have_subscription')
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="workers_off[]" value="{{$worker->id}}">
                                                                </div>
                                                            </td>
                                                        @else
                                                            <td>
                                                                <div class="text-center">
                                                                    <input type="checkbox" name="workers_on[]" value="{{$worker->id}}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    @lang('common.do_not_have_subscription')
                                                                </div>
                                                            </td>
                                                        @endif                                 
                                                    @endif
                                                    <td>{{$worker->name}} {{$worker->surname}}</td>
                                                    <td>{{$worker->email}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            @foreach($substartIntervals as $substartInterval)
                                @if ($substartInterval->workers)
                                    @if ($today > $substartInterval->start_date && $today > $substartInterval->end_date)


                                    <!--todo: coś tu? czemu puste?????-->



                                    @else
                                        {{ Form::hidden('substart_id', $substart->id) }}
                                        {{ Form::hidden('interval_id', $substartInterval->id) }}

                                        <div class="text-center" style="margin: 1rem;">
                                            <input type="submit" value="@lang('common.update')" class="btn btn-primary">                                    
                                            <div id="submit-warning" class="warning"></div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach

                        {{ Form::close() }}
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center">
                <h4>@lang('common.no_employee_assigned')</h4>
                <h5>@lang('common.go_to_register_code_generation_view')</h5>
                <div style="padding: 1rem;">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('boss/codes/') }}">
                        @lang('common.go_to')
                    </a>
                </div>
            </div>
        @endif
    @endforeach
</div>
@endsection