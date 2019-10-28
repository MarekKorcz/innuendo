@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('welcome.subscriptions')</h2>
            </div>
        </div>
    </div>
        
    <div class="container">
        <h2 class="text-center">@lang('welcome.massage_15')</h2>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/smile-beam')
                <h3>@lang('welcome.1_massage_15_per_month')</h3>
                <p>@lang('welcome.1_massage_15_price') <strong>@lang('welcome.5_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/laugh-beam')
                <h3>@lang('welcome.2_massages_15_per_month')</h3>
                <p>@lang('welcome.2_massages_15_price') <strong>@lang('welcome.10_discount')</strong></p>
            </div>
        </div>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/grin-hearts')
                <h3>@lang('welcome.3_massages_15_per_month')</h3>
                <p>@lang('welcome.3_massages_15_price') <strong>@lang('welcome.15_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/surprise')
                <h3>@lang('welcome.4_massages_15_per_month')</h3>
                <p>@lang('welcome.4_massages_15_price') <strong>@lang('welcome.20_discount')</strong></p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <div class="text-center" style="padding: 1rem;">
                    <h4>@lang('welcome.discount_button_paragraph')</h4>
                    
                    <a href="{{ URL::to('discounts') }}" class="btn pallet-1-3 btn-lg" style="color: white; margin-top: 1rem;">
                        @lang('welcome.all_discounts')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h2 class="text-center">@lang('welcome.massage_30')</h2>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/smile-beam')
                <h3>@lang('welcome.1_massage_30_per_month')</h3>
                <p>@lang('welcome.1_massage_30_price') <strong>@lang('welcome.5_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/laugh-beam')
                <h3>@lang('welcome.2_massages_30_per_month')</h3>
                <p>@lang('welcome.2_massages_30_price') <strong>@lang('welcome.10_discount')</strong></p>
            </div>
        </div>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/grin-hearts')
                <h3>@lang('welcome.3_massages_30_per_month')</h3>
                <p>@lang('welcome.3_massages_30_price') <strong>@lang('welcome.15_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/surprise')
                <h3>@lang('welcome.4_massages_30_per_month')</h3>
                <p>@lang('welcome.4_massages_30_price') <strong>@lang('welcome.20_discount')</strong></p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <div class="text-center" style="padding: 1rem;">
                    <h4>@lang('welcome.discount_button_paragraph')</h4>
                    <a href="{{ URL::to('discounts') }}" class="btn pallet-1-3 btn-lg" style="color: white; margin-top: 1rem;">
                        @lang('welcome.all_discounts')
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection