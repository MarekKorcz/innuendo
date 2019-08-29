@extends('layouts.app')
@section('content')

{!! Html::script('js/graphic_request.js') !!}

<div class="container">
    <div class="jumbotron" style="margin: 15px;">
        <h1 class="text-center">@lang('common.edit_graphic_request')</h1>

        {{ Form::open(['id' => 'request-form', 'action' => ['BossController@graphicRequestUpdate'], 'method' => 'POST']) }}
                
            <div class="form-group">
                <label for="start_time">@lang('common.start_time')</label>
                {{ Form::time('start_time', $graphicRequest->start_time, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>
            <div class="form-group">
                <label for="end_time">@lang('common.end_time')</label>
                {{ Form::time('end_time', $graphicRequest->end_time, array('class' => 'form-control')) }}
                <div class="warning"></div>
            </div>

            <div id="appointment-quantity-counter"></div>

            <p class="text-center">@lang('common.choose_from_within_our_employees')</p>
            <ul id="employees" style="padding-left: 12px; padding-right: 12px;">
                @foreach($graphicRequest->allEmployees as $employee)
                    @if ($employee->isChosen == true)
                        <li class="form-control" style="background-color: lightgreen;" data-active="true" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                    @else
                        <li class="form-control" value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</li>
                    @endif
                @endforeach
            </ul>
            <div id="employees-warning" class="warning"></div>

            <div class="form-group">
                <label for="comment">@lang('common.comment')</label>
                {{ Form::textarea('comment', $graphicRequest->comment, array('class' => 'form-control')) }}
            </div>
            
            @foreach($graphicRequest->allEmployees as $employee)
                @if ($employee->isChosen == true)
                    <input type="hidden" name="employees[]" value="{{$employee->id}}"/>
                @endif
            @endforeach
        
            {{ Form::hidden('graphic_request_id', $graphicRequest->id) }}
            {{ Form::hidden('_method', 'PUT') }}
            
            <div class="text-center">
                <input type="submit" value="@lang('common.update')" class="btn btn-primary">
            </div>

        {{ Form::close() }}
    </div>
</div>
@endsection