@extends('layouts.app')

@section('content')

{!! Html::script('js/welcome.js') !!}
{!! Html::style('css/welcome.css') !!}
    
    <!--Carousel Wrapper-->
    <div id="carousel-example-2" class="carousel slide carousel-fade" data-ride="carousel">
        <!--Indicators-->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-2" data-slide-to="1"></li>
            <li data-target="#carousel-example-2" data-slide-to="2"></li>
        </ol>
        <!--/.Indicators-->
        <!--Slides-->
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
                <div class="view">
                    <img class="d-block w-100" src="/img/background1.jpg">
                    <div class="mask rgba-black-light"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Light mask</h3>
                    <p>First text</p>
                </div>-->
            </div>
            <div class="carousel-item">
                <!--Mask color-->
                <div class="view">
                    <img class="d-block w-100" src="/img/background2.jpg">
                    <div class="mask rgba-black-strong"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Strong mask</h3>
                    <p>Secondary text</p>
                </div>-->
            </div>
            <div class="carousel-item">
                <!--Mask color-->
                <div class="view">
                    <img class="d-block w-100" src="/img/background3.jpg">
                    <div class="mask rgba-black-slight"></div>
                </div>
<!--                <div class="carousel-caption">
                    <h3 class="h3-responsive">Slight mask</h3>
                    <p>Third text</p>
                </div>-->
            </div>
        </div>
        <!--/.Slides-->
        <!--Controls-->
        <a class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        <!--/.Controls-->
    </div>
    <!--/.Carousel Wrapper-->
    
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h3 class="text-center">
                    @lang('welcome.header_paragraph')
                </h3>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row welcome">
            <div class="col-12">
                <h2 class="text-center">@lang('welcome.main_header')</h2>
            </div>
            <hr>
            <div class="col-12">
                <p class="lead text-center">@lang('welcome.first_paragraph')</p>
            </div>
        </div>
    </div>
    
    <div class="welcome">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <p class="lead">@lang('welcome.third_paragraph')</p>
                </div>
                <div class="col-sm-6 text-center box">
                    <img src="img/główna.jpg" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    
    <!--Fixed background-->
<!--    <div id="fixed">
        <div class="landing-text">
            <h1>BOOTSTRAP</h1>
            <h3>Learn the basic building blocks.</h3>
            <a href="#" class="btn btn-outline-secondary btn-lg">
                Get Started
            </a>
        </div>
    </div>-->
    
    <div class="container-fluid">
        <div class="row welcome text-center">
            <div class="col-12">
                <p class="lead text-center">@lang('welcome.second_paragraph')</p>
            </div>
            <div class="col-12">
                <p class="lead" style="font-size: 21px;">@lang('welcome.meeting_methodology_sixth_li')</p>
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid">
        <div class="row welcome text-center">
            <div class="col-12">
                <p class="lead" style="font-size: 27px;">
                    @lang('welcome.forth_paragraph')
                </p>
            </div>
        </div>
    </div>
    
    <div class="welcome">
        <div class="container">
            <div class="row" style="font-size: 24px;">
                <div class="col-sm-6" style="padding-bottom: 2rem;">
                    <p class="lead" style="font-size: 30px;">@lang('welcome.less')</p>
                    <ul>
                        <li>- @lang('welcome.staff_turnover')</li>
                        <li>- @lang('welcome.sick_leave')</li>
                        <li>- @lang('welcome.health_leave')</li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <p class="lead" style="font-size: 30px;">@lang('welcome.more')</p>
                    <ul>
                        <li>- @lang('welcome.employee_satisfaction')</li>
                        <li>- @lang('welcome.concentration_and_motivation')</li>
                        <li>- @lang('welcome.productivity_and_profits')</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center" style="padding-top: 1rem;">
                <h2>@lang('welcome.us_vs_other_header')</h2>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row welcome text-center">
            <div class="col-12">
                <h3 style="font-size: 33px;">@lang('welcome.our_site_header')</h3>
                
                <ul>
                    <li>
                        <h4>@lang('welcome.our_site_first_li')</h4>
                    </li>
                    <p>
                        @lang('welcome.our_site_first_li_p')
                        <a href="{{ URL::to('subscriptions') }}" target="_blank">
                            @lang('welcome.check_by_yourself')
                        </a>
                    </p>
                    
                    <li>
                        <h4>@lang('welcome.our_site_second_li')</h4>
                    </li>
                    <p>@lang('welcome.our_site_second_li_p')</p>
                    
                    <li>
                        <h4>@lang('welcome.our_site_third_li')</h4>
                    </li>
                    <p>
                        @lang('welcome.discount_paragraph')
                        <a href="{{ URL::to('discounts') }}" target="_blank">
                            @lang('welcome.see_by_yourself')
                        </a>
                    </p>
                    
                    <li>
                        <h4>@lang('welcome.our_site_fourth_li')</h4>
                    </li>
                    <!--<p>@lang('welcome.our_site_fourth_li_p')</p>-->
                    
                    
<!--                    <li>
                        <h4>@lang('welcome.our_site_fifth_li')</h4>
                    </li>
                    <p>@lang('welcome.our_site_fifth_li_p')</p>-->
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center" style="padding-top: 1rem;">
                <h2>
                    <a href="{{ URL::to('subscriptions') }}" target="_blank">
                        @lang('welcome.subscriptions')
                    </a>
                </h2>
            </div>
        </div>
    </div>  
    
    <div class="container">
        <h2 class="text-center">@lang('welcome.massage_20')</h2>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/laugh-beam')
                <h3>@lang('welcome.2_massages_20_per_month')</h3>
                <p>@lang('welcome.2_massages_20_price') <strong>@lang('welcome.10_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/surprise')
                <h3>@lang('welcome.4_massages_20_per_month')</h3>
                <p>@lang('welcome.4_massages_20_price') <strong>@lang('welcome.20_discount')</strong></p>
            </div>
        </div>
        <hr>
        <h2 class="text-center padding">@lang('welcome.massage_40')</h2>
        <div class="row text-center">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/smile-beam')
                <h3>@lang('welcome.1_massage_40_per_month')</h3>
                <p>@lang('welcome.1_massage_40_price') <strong>@lang('welcome.5_discount')</strong></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/grin-hearts')
                <h3>@lang('welcome.3_massages_40_per_month')</h3>
                <p>@lang('welcome.3_massages_40_price') <strong>@lang('welcome.15_discount')</strong></p>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center" style="padding: 1rem;">
                    <a href="{{ URL::to('subscriptions') }}" class="btn btn-lg pallet-1-3" style="color: white;">
                        @lang('welcome.all_subscriptions')
                    </a>
                </h2>
            </div>
        </div>
    </div>  
    
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center" style="padding-top: 1rem;">
                <h2>
                    <a href="{{ URL::to('discounts') }}" target="_blank">
                        @lang('welcome.additional_discounts')
                    </a>
                </h2>
            </div>
        </div>
    </div> 
    
    <div class="container">
        <h3 class="text-center">
            @lang('welcome.discount_paragraph')
        </h3>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/smile-beam')
                <h3>@lang('welcome.from_five')</h3>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/laugh-beam')
                <h3>@lang('welcome.from_twenty_five')</h3>
            </div>
        </div>
        <hr>
        <div class="row text-center padding">
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/grin-hearts')
                <h3>@lang('welcome.from_fifty')</h3>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                @svg('regular/surprise')
                <h3>@lang('welcome.from_hundred')</h3>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center" style="padding: 1rem;">
                    <a href="{{ URL::to('discounts') }}" class="btn btn-lg pallet-1-3" style="color: white;">
                        @lang('welcome.all_discounts')
                    </a>
                </h2>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12 text-center" style="padding-top: 1rem;">
                <h2>@lang('welcome.when_we_arrive_header')</h2>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row welcome text-center" style="padding-bottom: 3rem;">
            <div class="col-12">
                <ul>
                    <li>
                        <h4>@lang('welcome.meeting_methodology_first_li')</h4>
                    </li>
                    
                    <li>
                        <h4>@lang('welcome.meeting_methodology_second_li')</h4>
                    </li>
                    
                    <li>
                        <h4>@lang('welcome.meeting_methodology_third_li')</h4>
                    </li>
                    
                    <li>
                        <h4>@lang('welcome.meeting_methodology_fourth_li')</h4>
                    </li>
                    
                    <li>
                        <h4>@lang('welcome.meeting_methodology_fifth_li')</h4>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
<!--    <div class="padding welcome">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img src="img/column2.jpg" class="img-fluid">
                </div>
                <div class="col-sm-6 text-center">
                    <h4>@lang('welcome.about_header')</h4>
                    <p class="lead">@lang('welcome.about_paragraph')</p>
                </div>
            </div>
        </div>
    </div>-->

    @if ($showBanner)
        @include('layouts.banner')
    @endif
@endsection