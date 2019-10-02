@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('welcome.discounts')</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <h3 class="text-center">@lang('welcome.discount_paragraph')</h3>
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

@endsection