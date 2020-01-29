@extends('layouts.app')
@section('content')
<div class="container">

    <div class="row text-center" style="padding: 1rem 0 1rem 0;">
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/property/index') }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.all_properties')
            </a>
        </div>
        <div class="col-4"></div>
    </div>

    <div class="jumbotron">
        <div class="text-center">
            <h1>@lang('common.edit_property') - 
                <a href="{{ URL::to('/property/' . $property->id) }}">
                    {{$property->name}}
                </a>
            </h1>
        </div>

        <div class="row">
            <div class="col-1"></div>            
            <div class="col-10">
                {{ Form::open(['action' => ['PropertyController@update', $property->id], 'method' => 'POST']) }}

                    <div class="form-group">
                        <label for="name">@lang('common.name'):</label>
                        {{ Form::text('name', $property->name, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="street">@lang('common.street'):</label>
                        {{ Form::text('street', $property->street, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="street_number">@lang('common.street_number'):</label>
                        {{ Form::text('street_number', $property->street_number, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="house_number">@lang('common.house_number'):</label>
                        {{ Form::text('house_number', $property->house_number, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="city">@lang('common.city'):</label>
                        {{ Form::text('city', $property->city, array('class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="user">@lang('common.bosses'):</label>
                        <select id="user" name="boss_id" class="form-control">
                            @if ($property->boss_id == 0 || $property->boss_id == null)
                                <option value="0" selected="true">@lang('common.public')</option>
                            @else
                                <option value="0">@lang('common.public')</option>
                            @endif
                            @foreach ($property->bosses as $boss)
                                @if ($boss->id == $property->boss_id)
                                    <option value="{{$boss->id}}" selected="true">{{$boss->name}} {{$boss->surname}}</option>
                                @else
                                    <option value="{{$boss->id}}">{{$boss->name}} {{$boss->surname}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{ Form::hidden('_method', 'PUT') }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>            
            <div class="col-1"></div>            
        </div>
    </div>
</div>
@endsection