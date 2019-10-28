@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding: 2rem;">
        <div class="col-4">
            <a href="{{ URL::previous() }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.go_back')
            </a>
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>


    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="jumbotron">
                
                <div class="text-center">
                    <h1>@lang('common.appointment_edit')</h1>
                </div>

                {{ Form::open(['action' => ['WorkerController@appointmentUpdate', $appointment->id], 'method' => 'POST']) }}
                
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div class="form-group">
                            <label for="start_time">@lang('common.start_time'):</label>
                            {{ Form::time('start_time', $appointment->start_time, array('class' => 'form-control')) }}
                        </div>
                        <div class="form-group">
                            <label for="end_time">@lang('common.end_time'):</label>
                            {{ Form::time('end_time', $appointment->end_time, array('class' => 'form-control')) }}
                        </div>
                        <div class="form-group">
                            <label for="item">@lang('common.items'):</label>
                            <select name="item" class="form-control">
                                @foreach ($items as $item)
                                    @if ($item['isActive'])
                                        <option value="{{$item['key']}}" selected="selected">{{$item['value']}}</option>
                                    @else
                                        <option value="{{$item['key']}}">{{$item['value']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="item">@lang('common.status'):</label>
                            <select id="appointment-status" name="status" class="form-control">
                                @foreach ($statuses as $status)
                                    @if ($status['isActive'])
                                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}" selected="selected">{{$status['value']}}</option>
                                    @else
                                        <option value="{{$status['key']}}" data-appointment="{{$appointment->id}}">{{$status['value']}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        @if ($appointment->id)
                            {{ Form::hidden('appointmentId', $appointment->id) }}
                        @else
                            {{ Form::hidden('appointmentId', Input::old('appointmentId')) }}
                        @endif

                        {{ Form::hidden('_method', 'PUT') }}

                        <div class="text-center">
                            <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>
                    
                {{ Form::close() }}
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection