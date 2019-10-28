@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/property/index') }}" class="btn pallet-1-3" style="color: white;">
                    @lang('common.all_properties')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.create_property')</h1>

    {{ Form::open(['action' => 'PropertyController@store', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="name">@lang('common.name')</label>
            {{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="description">@lang('common.description')</label>
            {{ Form::textarea('description', Input::old('description'), array('id' => 'article-ckeditor', 'class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="street">@lang('common.street')</label>
            {{ Form::text('street', Input::old('street'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="street_number">@lang('common.street_number')</label>
            {{ Form::text('street_number', Input::old('street_number'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="house_number">@lang('common.house_number')</label>
            {{ Form::text('house_number', Input::old('house_number'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="city">@lang('common.city')</label>
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

        <input type="submit" value="@lang('common.create')" class="btn pallet-1-3" style="color: white;">

    {{ Form::close() }}

</div>
@endsection