@extends('layouts.app')
@section('content')

{!! Html::style('css/property_show.css') !!}
{!! Html::script('js/property_show.js') !!}

<div class="container">
        
        
        
        <div class="jumbotron">
            <div class="row text-center" style="padding-bottom: 2rem;">
                <div class="col-4">
                    <a class="btn btn-danger delete-property" style="color: white;" data-property_id="{{$property->id}}">
                        @lang('common.delete')
                    </a>
                </div>
                <div class="col-4">
                    @if ($property->canShow == 0)
                        <a class="btn btn-success" style="margin: 9px;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                            @lang('common.show_publicly')
                        </a>
                    @else
                        <a class="btn btn-success" style="margin: 9px;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                            @lang('common.do_not_show_publicly')
                        </a>
                    @endif
                </div>
                <div class="col-4">
                    <a class="btn btn-success" href="{{ URL::to('property/' . $property->id . '/edit') }}">
                        @lang('common.edit')
                    </a>
                </div>
            </div>
            <div class="text-center">
                <h1 style="padding-bottom: 1rem;">@lang('common.property_values'):</h1>
                <p>
                    @lang('common.owner'):
                    <strong style="font-size: 21px;">
                        @if ($property->boss !== null)
                            {{ $property->boss->name }} {{ $property->boss->surname }}
                        @else
                            @lang('common.public')
                        @endif
                    </strong>
                </p>
                <p>
                    @lang('common.label'):
                    <strong style="font-size: 21px;">
                        {{$property->name}}
                    </strong>
                </p>
                <p>
                    <strong>
                        {!! $property->description !!}
                    </strong>
                </p>
                <p>
                    @lang('common.address') : 
                    <strong>
                        {{$property->street}} 
                        {{$property->street_number}} / 
                        {{$property->house_number}} 
                        {{$property->city}}
                    </strong>
                </p>
            </div>
        </div>
        
    <div class="jumbotron">
        
        <div class="row text-center" style="padding-bottom: 2rem;">
            <div class="col-4"></div>
            <div class="col-4">
                <a class="btn btn-success" href="{{ URL::to('subscription/create/' . $property->id) }}">
                    @lang('common.create_subscription')
                </a>
            </div>
            <div class="col-4"></div>
        </div>
        
        <div class="text-center" style="padding-bottom: 1rem;">
            <h1>@lang('common.subscriptions'):</h1>
        </div>
        
        @if (count($subscriptions) > 0)
            <table class="table table-striped table-bordered">
                <tr class="highlight">
                    <th>@lang('common.name'):</th>
                    <th>@lang('common.description'):</th>
                    <th>@lang('common.old_price'):</th>
                    <th>@lang('common.new_price'):</th>
                    <th>@lang('common.quantity'):</th>
                    <th>@lang('common.duration'):</th>
                    <th>@lang('common.is_active'):</th>
                </tr>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{!! $subscription->name !!}</td>
                            <td>{!! $subscription->description !!}</td>
                            <td>{{ $subscription->old_price }}</td>
                            <td>{{ $subscription->new_price }}</td>
                            <td>{{ $subscription->quantity }}</td>
                            <td>{{ $subscription->duration }}</td>
                            <td>
                                @if ($subscription->isChosen == true)
                                    @lang('common.yes')
                                @else 
                                    @lang('common.no')
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        @else
            <div class="text-center">
                <h4>@lang('common.there_is_no_subscriptions_attached')</h4>
            </div>
        @endif
    </div>
        
    @if ($calendars)
        @foreach ($calendars as $calendar)
            <div class="jumbotron">
                
                <div class="row text-center" style="padding-bottom: 2rem;">
                    <div class="col-4"></div>
                    <div class="col-4">
                        @if ($calendar->isActive)
                            {!!Form::open(['action' => ['CalendarController@deactivate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                {{ Form::hidden('property_id', $property->id) }}
                                {{ Form::hidden('_method', 'POST') }}
                                <input type="submit" value="@lang('common.deactivate_calendar')" class="btn btn-primary">
                            {!!Form::close()!!}
                        @else
                            {!!Form::open(['action' => ['CalendarController@activate', $calendar->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                                {{ Form::hidden('property_id', $property->id) }}
                                {{ Form::hidden('_method', 'POST') }}
                                <input type="submit" value="@lang('common.activate_calendar')" class="btn btn-success">
                            {!!Form::close()!!}
                        @endif
                    </div>
                    <div class="col-4">
                        <a class="btn btn-danger delete-calendar" style="color: white;" data-calendar_id="{{$calendar->id}}">
                            @lang('common.delete')
                        </a>
                    </div>
                </div>
                
                @if ($calendar->employee_id != null)
                    <div class="text-center" style="margin-bottom: 40px;">
                        <h2 style="margin-bottom: 15px;">
                            @lang('common.calendar_assigned_to'):
                            <a href="{{ URL::to('employee/' . $employees[$calendar->id]->slug) }}">
                                {{$employees[$calendar->id]->name}}
                            </a>
                        </h2>
                        @if (count($years[$calendar->id]) > 0)
                            <h3>@lang('common.years') :</h3>
                        @endif
                    </div>
                    @if (count($years[$calendar->id]) > 0)
                        <div class="list-group">
                            @foreach ($years[$calendar->id] as $year)
                                <a class="list-group-item text-center" href="{{ URL::to('year/show/' . $year->id) }}">
                                    <h4>{{$year->year}}</h4>
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="text-center" style="padding-top: 30px;">
                        <a class="btn btn-success" href="{{ action('YearController@create', $calendar->id) }}">
                            @lang('common.add_year')
                        </a>
                    </div>
                @else
                    <h1 class="text-center">@lang('common.new_calendar')</h1>
                    <div class="text-center" style="padding-top: 30px;">
                        <a class="btn btn-primary" href="{{ action('EmployeeController@assign', $calendar->id) }}">
                            @lang('common.assign_calendar_to_employee')
                        </a>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
        <a class="btn btn-success" href="{{ action('CalendarController@create', $property->id) }}">
            @lang('common.create_calendar')
        </a>
    </div>
    
    <div id="deleteProperty" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.property_delete')</h4>
                <button id="deletePropertyCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="deleteCalendar" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.calendar_delete')</h4>
                <button id="deleteCalendarCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
    
</div>
@endsection