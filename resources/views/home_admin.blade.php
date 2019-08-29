@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="card-header text-center">
        <span style="font-size: 27px;">
            @lang('navbar.my_account') 
        </span> 
        - @lang('common.logged_in_as') 
        <strong>
            {{$user->name}} {{$user->surname}}
        </strong>
    </div>
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.create_boss_with_first_property')</h4>
                <p class="card-text text-center">
                    @lang('common.create_boss_with_first_property_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/boss/create') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.bosses_list')</h4>
                <p class="card-text text-center">
                    @lang('common.bosses_list_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/boss/list') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.employees_list')</h4>
                <p class="card-text text-center">
                    @lang('common.employees_list_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/employee/list') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.users_list')</h4>
                <p class="card-text text-center">
                    @lang('common.users_list_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/user/list') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.all_properties')</h4>
                <p class="card-text text-center">
                    @lang('common.all_properties_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/property/index') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.subscriptions')</h4>
                <p class="card-text text-center">
                    @lang('common.subscriptions_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/subscription/index') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.all_graphic_requests')</h4>
                <p class="card-text text-center">
                    @lang('common.all_graphic_requests_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('admin/graphic-requests') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">@lang('common.approved_messages')</h4>
                <p class="card-text text-center">
                    @lang('common.approved_messages_description')
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('admin/approve/messages') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection