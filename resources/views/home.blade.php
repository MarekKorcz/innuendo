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
                        <h4 class="card-title text-center">@lang('common.schedules')</h4>
                        <p class="card-text text-center">
                            @lang('common.schedules_with_property_description_and_executor')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-1-3 btn-lg" style="color: white;" href="{{ URL::to('/user/properties') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
    
    @if ($showBanner)
        @include('layouts.banner')
    @endif
</div>
@endsection