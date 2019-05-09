@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                <a href="{{ URL::to('/property/index') }}" class="btn btn-primary">
                    View All Properties
                </a>
            </li>
        </ul>
    </nav>

    <h1>Edit the Property</h1>

    {{ Form::open(['action' => ['PropertyController@update', $property->id], 'method' => 'POST']) }}

        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $property->name, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', $property->description, array('id' => 'article-ckeditor', 'class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', $property->email, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('phone_number', 'Phone number') }}
            {{ Form::number('phone_number', $property->phone_number, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('street', 'Street') }}
            {{ Form::text('street', $property->street, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('street_number', 'Street number') }}
            {{ Form::text('street_number', $property->street_number, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('house_number', 'House number') }}
            {{ Form::text('house_number', $property->house_number, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('post_code', 'Post Code') }}
            {{ Form::text('post_code', $property->post_code, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('city', 'City') }}
            {{ Form::text('city', $property->city, array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            {{ Form::label('user', 'Users:') }}
            <select id="user" name="user" class="form-control">
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
        {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>
@endsection