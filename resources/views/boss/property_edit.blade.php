@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_property_edit.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding: 1rem 0 1rem 0;">
        <div class="col-4">
            <a href="{{ URL::to('/boss/subscription/list/' . $property->id . '/0') }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.go_back')
            </a> 
        </div>
        <div class="col-4"></div>
        <div class="col-4"></div>
    </div>
    
    <div class="jumbotron" style="margin: 15px;">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                
                <div class="text-center">
                    <h1>@lang('navbar.property_edit')</h1>
                </div>

                {{ Form::open(['id' => 'property-edit', 'action' => ['BossController@propertyUpdate'], 'method' => 'POST']) }}

                    <div class="form-group">
                        <label for="name">@lang('common.company_name'):</label>
                        {{ Form::text('name', $property->name, array('class' => 'form-control', 'id' => 'name')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street">@lang('common.street'):</label>
                        {{ Form::text('street', $property->street, array('class' => 'form-control', 'id' => 'street')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="street_number">@lang('common.street_number'):</label>
                        {{ Form::text('street_number', $property->street_number, array('class' => 'form-control', 'id' => 'street_number')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="house_number">@lang('common.house_number'):</label>
                        {{ Form::text('house_number', $property->house_number, array('class' => 'form-control', 'id' => 'house_number')) }}
                        <div class="warning"></div>
                    </div>
                    <div class="form-group">
                        <label for="city">@lang('common.city'):</label>
                        {{ Form::text('city', $property->city, array('class' => 'form-control', 'id' => 'city')) }}
                        <div class="warning"></div>
                    </div>

                    {{ Form::hidden('property_id', $property->id) }}
                    {{ Form::hidden('_method', 'PUT') }}

                    <div class="text-center" style="padding-top: 1rem;">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-1-3" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection