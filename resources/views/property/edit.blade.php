@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/property/index') }}" class="btn btn-primary">
                    @lang('common.all_properties')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.edit_property')</h1>

    {{ Form::open(['action' => ['PropertyController@update', $property->id], 'method' => 'POST']) }}

        <div class="form-group">
            <label for="name">@lang('common.name')</label>
            {{ Form::text('name', $property->name, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="description">@lang('common.description')</label>
            {{ Form::textarea('description', $property->description, array('id' => 'article-ckeditor', 'class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="street">@lang('common.street')</label>
            {{ Form::text('street', $property->street, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="street_number">@lang('common.street_number')</label>
            {{ Form::text('street_number', $property->street_number, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="house_number">@lang('common.house_number')</label>
            {{ Form::text('house_number', $property->house_number, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="city">@lang('common.city')</label>
            {{ Form::text('city', $property->city, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="user">@lang('common.users')</label>
            <select id="user" name="user" class="form-control">
                @if ($property->boss_id == 0 || $property->boss_id == null)
                    <option value="0" selected="true">@lang('common.public')</option>
                @else
                    <option value="0">@lang('common.public')</option>
                @endif
                @foreach ($property->users as $user)
                    @if ($user->id == $property->boss_id)
                        <option value="{{$user->id}}" selected="true">{{$user->name}} {{$user->surname}}</option>
                    @else
                        <option value="{{$user->id}}">{{$user->name}} {{$user->surname}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    
        {{ Form::hidden('_method', 'PUT') }}
        
        <input type="submit" value="@lang('common.update')" class="btn btn-primary">

    {{ Form::close() }}

</div>
@endsection