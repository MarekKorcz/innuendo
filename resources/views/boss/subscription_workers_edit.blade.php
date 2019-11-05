@extends('layouts.app')
@section('content')

{!! Html::style('css/subscription_workers_edit.css') !!}
{!! Html::script('js/subscription_workers_edit.js') !!}

<div class="container" style="padding: 1rem 0 1rem 0;">
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            
            <div class="text-center" style="padding: 1rem 0 1rem 0;">
                <h2>
                    @lang('common.people_assigned_to_subscription'): {!! $subscription->name !!}
                </h2>
            </div>
            
            @if (count($substartIntervals) > 1)
                <div class="row text-center">
                    <div class="col-3"></div>
                        <div class="col-6">
                            <label for="timePeriod" style="font-size: 24px;">@lang('common.select_a_billing_period'):</label>                        
                            <ul id="timePeriod" class="list-group">
                                @foreach ($substartIntervals as $substartInterval)
                                    <a href="{{ URL::to('/boss/subscription/workers/edit/' . $substart->id . '/' . $substartInterval->id) }}" style="margin: 1px;">
                                        @if ($substartInterval['isChecked'] == true)
                                            <li class="list-group-item clicked">
                                                {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                            </li>
                                        @else
                                            <li class="list-group-item">
                                                {{$substartInterval->start_date->format("Y-m-d")}} - {{$substartInterval->end_date->format("Y-m-d")}}
                                            </li>
                                        @endif
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    <div class="col-3"></div>
                </div>
            
                <div class="text-center" style="padding-top: 2rem;">
                    <h2>
                        @lang('common.employees')
                        @if ($chosenInterval !== null)
                            @lang('common.asigned_from')
                            {{$chosenInterval->start_date->format('Y-m-d')}} 
                            @lang('common.to')
                            {{$chosenInterval->end_date->format('Y-m-d')}}
                        @endif
                    </h2>
                </div>
            @endif

            @if ($chosenInterval !== null)
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
                                        @if (count($chosenInterval->workers) > 0)
                                            @foreach($chosenInterval->workers as $worker)
                                                <tr>
                                                    @if ($today > $chosenInterval->start_date && $today > $chosenInterval->end_date)
                                                        @if ($worker->withSubscription == true)
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
                                                    @elseif ($today >= $chosenInterval->start_date && $today <= $chosenInterval->end_date)
                                                        @if ($worker->withSubscription == true)
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
                                                        @if ($worker->withSubscription == true)
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
                                    @else
                                        @if (count($chosenInterval->workers) > 0)
                                            @foreach($chosenInterval->workers as $worker)
                                                <tr>
                                                    @if ($today >= $chosenInterval->start_date && $today <= $chosenInterval->end_date)
                                                        @if ($worker->withSubscription == true)
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
                                    @endif
                                </div>

                                @if (count($chosenInterval->workers) > 0)
                                    <div class="text-center">
                                        {{ Form::hidden('substart_id', $substart->id) }}
                                        {{ Form::hidden('interval_id', $chosenInterval->id) }}

                                        <div class="text-center" style="margin: 1rem;">
                                            <input type="submit" value="@lang('common.update')" class="btn btn-primary">                                    
                                            <div id="submit-warning" class="warning"></div>
                                        </div>
                                    </div>
                                @endif

                            {{ Form::close() }}
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection