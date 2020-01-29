@extends('layouts.app')
@section('content')

{!! Html::script('js/admin_boss_show.js') !!}

<div class="container" style="padding: 18px;">
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div style="padding-top: 1rem;">
                <div class="text-center">
                    <h2>@lang('common.boss'):</h2>
                    <p>{{$boss->created_at}}</p>
                </div>
                
                <div class="row" style="padding-bottom: 1rem;">
                    <div class="col-1"></div>
                    <div class="col-10">
                        {{ Form::open(['action' => ['AdminController@bossUpdate'], 'method' => 'POST']) }}

                            <div class="form-group">
                                <label for="name">@lang('common.name'):</label>
                                <input id="name" name="name" class="form-control" type="text" value="{{$boss->name}}">
                            </div>

                            <div class="form-group">
                                <label for="surname">@lang('common.surname'):</label>
                                <input id="surname" name="surname" class="form-control" type="text" value="{{$boss->surname}}">
                            </div>

                            <div class="form-group">
                                <label for="email">@lang('common.email_address'):</label>
                                <input id="email" name="email" class="form-control" type="text" value="{{$boss->email}}">
                            </div>

                            <div class="form-group">
                                <label for="phone_number">@lang('common.phone_number'):</label>
                                <input id="phone_number" name="phone_number" class="form-control" type="text" value="{{$boss->phone_number}}">
                            </div>

                            <div class="form-group">
                                <label for="is-boss">@lang('common.is_boss'):</label>
                                <select id="is-boss" data-boss_id="{{$boss->id}}" name="is_boss" class="form-control">
                                    @if ($boss->isBoss == 1)
                                        <option value="true" selected="true">@lang('common.true')</option>
                                        <option value="false">@lang('common.false')</option>
                                    @else
                                        <option value="false" selected="true">@lang('common.false')</option>
                                        <option value="true">@lang('common.true')</option>
                                    @endif
                                </select>
                            </div>
                        
                            <div id="new-boss-element" class="form-group"></div>

                            {{ Form::hidden('boss_id', $boss->id) }}
                            {{ Form::hidden('_method', 'PUT') }}

                            <div class="text-center">
                                <input type="submit" value="@lang('common.update')" class="btn pallet-2-4" style="color: white;">
                            </div>

                        {{ Form::close() }}
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
            
            <hr>

            <div style="padding-bottom: 1rem;">
                <div class="text-center">
                    <h2>@lang('common.properties_owned_by_boss'):</h2>
                </div>

                @if (count($properties) > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.name')</td>
                                <td>@lang('common.street')</td>
                                <td>@lang('common.created_at')</td>
                                <td>@lang('common.action')</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>{{$property->name}}</td>
                                    <td>{{$property->street}}</td>
                                    <td>{{$property->created_at}}</td>
                                    <td>
                                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/property/' . $property->id) }}">
                                            @lang('common.show')
                                        </a>
                                        <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/property/' . $property->id . '/edit') }}">
                                            @lang('common.edit')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center" style="padding: 1rem 0 1rem 0;">
                        <p>@lang('common.no_properties_description')</p>
                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/property/index') }}">
                            @lang('common.properties')
                        </a>
                    </div>
                @endif
            </div>
            
            <hr>
            
            <div style="padding-bottom: 1rem;">
                <div class="text-center">
                    <h2>@lang('common.employees'):</h2>
                </div>

                @if (count($workers) > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.name')</td>
                                <td>@lang('common.phone_number')</td>
                                <td>@lang('common.email_address')</td>
                                <td>@lang('common.created_at')</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($workers as $worker)
                                <tr>
                                    <td>
                                        <a href="{{ URL::to('/admin/user/show/' . $worker->id) }}">
                                            {{$worker->name}} {{$worker->surname}}
                                        </a>
                                    </td>
                                    <td>{{$worker->phone_number}}</td>
                                    <td>{{$worker->email}}</td>
                                    <td>{{$worker->created_at}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">
                        <p>
                            @lang('common.no_employees_description')
                        </p>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection