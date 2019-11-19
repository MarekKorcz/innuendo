@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="row text-center" style="padding-top: 1rem;">
        <div class="col-4"></div>
        <div class="col-4">
            <a href="{{ URL::to('/property/index') }}" class="btn pallet-1-3" style="color: white;">
                @lang('common.all_properties')
            </a>
        </div>
        <div class="col-4"></div>
    </div>

    <div class="row" style="padding: 1rem 0 1rem 0;">
        <div class="col-1"></div>
        <div class="col-10">
            
            <div class="text-center">
                <h1>@lang('common.create_property')</h1>
            </div>

            {{ Form::open(['action' => 'PropertyController@store', 'method' => 'POST']) }}

                <div class="form-group">
                    <label for="name">@lang('common.name'):</label>
                    {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="description">@lang('common.description'):</label>
                    {{ Form::textarea('description', Input::old('description'), array('id' => 'article-ckeditor', 'class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="street">@lang('common.street'):</label>
                    {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="street_number">@lang('common.street_number'):</label>
                    {{ Form::text('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="house_number">@lang('common.house_number'):</label>
                    {{ Form::text('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="city">@lang('common.city'):</label>
                    {{ Form::text('city', Input::old('city'), array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="user">@lang('common.users'):</label>
                    <select id="user" name="user" class="form-control">
                        <option value="0"></option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}} {{$user->surname}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-center">
                    <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">
                </div>

            {{ Form::close() }}
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection