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
                <h4 class="card-title text-center">@lang('common.schedules')</h4>
                <p class="card-text text-center">
                    @lang('common.schedules_list_in_properties')
                </p>
                <div class="text-center">
                    <a class="btn btn-success btn-lg" href="{{ URL::to('/employee/backend-graphic') }}">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection