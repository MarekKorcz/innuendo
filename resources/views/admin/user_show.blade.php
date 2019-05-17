@extends('layouts.app')
@section('content')

{!! Html::style('css/appointment_show.css') !!}

<div class="container">

    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>Użytkownik - <strong>{{$user->name}}</strong></h2>
        </div>
        
        {{ Form::open(['action' => ['AdminController@userEdit', $user->id], 'method' => 'POST']) }}

            <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" name="name" class="form-control" type="text" value="{{$user->name}}">
            </div>
        
            <div class="form-group">
                <label for="surname">Surname:</label>
                <input id="surname" name="surname" class="form-control" type="text" value="{{$user->surname}}">
            </div>
        
            <div class="form-group">
                <label for="slug">Slug:</label>
                <input id="slug" name="slug" class="form-control" type="text" value="{{$user->slug}}">
            </div>
        
            <div class="form-group">
                <label for="email">Email:</label>
                <input id="email" name="email" class="form-control" type="text" value="{{$user->email}}">
            </div>
        
            <div class="form-group">
                <label for="phone_number">Phone:</label>
                <input id="phone_number" name="phone_number" class="form-control" type="text" value="{{$user->phone_number}}">
            </div>
        
            <div class="form-group">
                <label for="isAdmin">isAdmin:</label>
                <select id="isAdmin" name="isAdmin" class="form-control">
                    @if ($user->isAdmin == 1)
                        <option value="true" selected="true">true</option>
                        <option value="false">false</option>
                    @else
                        <option value="false" selected="true">false</option>
                        <option value="true">true</option>
                    @endif
                </select>
            </div>
        
            <div class="form-group">
                <label for="isEmployee">isEmployee:</label>
                <select id="isEmployee" name="isEmployee" class="form-control">
                    @if ($user->isEmployee == 1)
                        <option value="true" selected="true">true</option>
                        <option value="false">false</option>
                    @else
                        <option value="false" selected="true">false</option>
                        <option value="true">true</option>
                    @endif
                </select>
            </div>
        
            <div class="form-group">
                <label for="isBoss">isBoss:</label>
                <select id="isBoss" name="isBoss" class="form-control">
                    @if ($user->isBoss == 1)
                        <option value="true" selected="true">true</option>
                        <option value="false">false</option>
                    @else
                        <option value="false" selected="true">false</option>
                        <option value="true">true</option>
                    @endif
                </select>
            </div>
        
            <div class="form-group">
                <label for="code">Code:</label>
                <input id="code" name="code" class="form-control" type="text" value="{{$user->code}}">
            </div>
        
            <div class="form-group">
                <label for="boss_id">Boss:</label>
                <select id="boss_id" name="boss_id" class="form-control">
                    @foreach ($bosses as $boss)
                        @if ($user->boss_id == $boss->id)
                            <option value="{{$boss->id}}" selected="true">{{$boss->name}} {{$boss->surname}}</option>
                        @else
                            <option value="{{$boss->id}}">{{$boss->name}} {{$boss->surname}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            {{ Form::hidden('_method', 'PUT') }}

            {{ Form::submit('Zaktualizuj', array('class' => 'btn btn-primary')) }}

        {{ Form::close() }}
        
        
    </div>
</div>
@endsection