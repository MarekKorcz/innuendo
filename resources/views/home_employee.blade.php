@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
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
                        <h4 class="card-title text-center">@lang('common.schedules')</h4>
                        <p class="card-text text-center">
                            @lang('common.schedules_list_in_properties')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/employee/backend-graphic') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.users')</h4>
                        <p class="card-text text-center">
                            @lang('common.users_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/employee/backend-users/index') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection