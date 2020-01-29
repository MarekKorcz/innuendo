@extends('layouts.app')
@section('content')

{!! Html::style('css/appointment_show.css') !!}

<div class="container">

    <div class="jumbotron" style="margin-top: 2rem;">
        <div class="text-center" style="margin-bottom: 30px;">
            <h2>
                {{$user->name}} {{$user->surname}}
            </h2>
            <p>
                <a href="{{ URL::to('/admin/boss/show/' . $userBoss->id) }}">
                    ({{$userBoss->name}} {{$userBoss->surname}})
                </a>
            </p>
        </div>
        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                {{ Form::open(['action' => ['AdminController@userUpdate'], 'method' => 'POST']) }}

                    <div class="form-group">
                        <label for="name">@lang('common.name'):</label>
                        <input id="name" name="name" class="form-control" type="text" value="{{$user->name}}">
                    </div>

                    <div class="form-group">
                        <label for="surname">@lang('common.surname'):</label>
                        <input id="surname" name="surname" class="form-control" type="text" value="{{$user->surname}}">
                    </div>

                    <div class="form-group">
                        <label for="email">@lang('common.email_address'):</label>
                        <input id="email" name="email" class="form-control" type="text" value="{{$user->email}}">
                    </div>

                    <div class="form-group">
                        <label for="phone_number">@lang('common.phone_number'):</label>
                        <input id="phone_number" name="phone_number" class="form-control" type="text" value="{{$user->phone_number}}">
                    </div>

                    <div class="form-group">
                        <label for="isBoss">@lang('common.is_boss'):</label>
                        <select id="isBoss" name="isBoss" class="form-control">
                            @if ($user->isBoss == 1)
                                <option value="true" selected="true">@lang('common.true')</option>
                                <option value="false">@lang('common.false')</option>
                            @else
                                <option value="false" selected="true">@lang('common.false')</option>
                                <option value="true">@lang('common.true')</option>
                            @endif
                        </select>
                    </div>

                    @if (count($bosses) > 0)
                        <div class="form-group">
                            <label for="boss_id">@lang('common.boss'):</label>
                            <select id="boss_id" name="boss_id" class="form-control">
                                @foreach ($bosses as $boss)
                                    @if ($userBoss->id == $boss->id)
                                        <option value="{{$boss->id}}" selected="true">{{$boss->name}} {{$boss->surname}}</option>
                                    @else
                                        <option value="{{$boss->id}}">{{$boss->name}} {{$boss->surname}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="text-center">
                            <p>
                                @lang('common.no_bosses_description')
                            </p>
                        </div>
                    @endif

                    {{ Form::hidden('user_id', $user->id) }}
                    {{ Form::hidden('_method', 'PUT') }}

                    <div class="text-center">
                        <input type="submit" value="@lang('common.update')" class="btn pallet-2-4" style="color: white;">
                    </div>

                {{ Form::close() }}
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection