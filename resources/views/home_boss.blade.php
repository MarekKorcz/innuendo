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
                @if ($user->is_approved == 1)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">@lang('common.appointments')</h4>
                            <p class="card-text text-center">
                                @lang('common.your_appointments_view') 
                            </p>
                            <div class="text-center">
                                <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/appointment/index') }}">
                                    @lang('common.show')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">@lang('common.graphic_requests_management_panel')</h4>
                            <p class="card-text text-center">
                                @lang('common.graphic_requests_management_panel_description')
                            </p>
                            <div class="text-center">
                                <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('boss/graphic-requests') }}">
                                    @lang('common.show')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">@lang('common.register_codes_generation')</h4>
                            <p class="card-text text-center">
                                @lang('common.register_codes_generation_description')
                            </p>
                            <div class="text-center">
                                <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('boss/codes/') }}">
                                    @lang('common.show')
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title text-center">@lang('common.make_an_appointment_with_us')</h4>
                            <p class="card-text text-center">
                                @lang('common.make_an_appointment_with_us_description')
                            </p>
                            <div class="text-center">
                                <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/boss/approve/messages') }}">
                                    @lang('common.show')
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-1"></div>
    </div>
    
    @if ($showBanner)
        @include('layouts.banner')
    @endif
</div>
@endsection