@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center">
                <h1>
                    @lang('about.about_us')
                </h1>
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="row" style="padding: 2rem 0 3rem 0;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10 text-center">
                <p style="font-size: 21px;">
                    <strong>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }}
                    </strong>
                    @lang('about.description_1')
                </p>
                <p>
                    @lang('about.description_2')
                    <a href="{{ route('subscriptions') }}">@lang('navbar.subscriptions')</a>
                    @lang('common.and')
                    <a href="{{ route('discounts') }}">@lang('navbar.discounts')</a>
                </p>
                <p>
                    @lang('about.description_3')
                    <a href="{{ route('regulations') }}">
                        @lang('about.regulations_link_description')
                    </a> 
                    @lang('about.description_4')
                </p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    @if ($showBanner)
        @include('layouts.banner')
    @endif

@endsection