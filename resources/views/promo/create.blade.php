@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}
{!! Html::script('js/promo_create.js') !!}

<div class="container jumbotron">

    <div class="text-center" style="padding-bottom: 1rem;">
        <h1>@lang('common.create_promo')</h1>
    </div>

    {{ Form::open(['id' => 'promo-code', 'action' => 'AdminController@promoStore', 'method' => 'POST']) }}

        <div class="form-group">
            <label for="title">@lang('common.title') :</label>
            {{ Form::text('title', Input::old('title'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="title">@lang('common.title_en') :</label>
            {{ Form::text('title_en', Input::old('title_en'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="description">@lang('common.description') :</label>
            {{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="description_en">@lang('common.description_en') :</label>
            {{ Form::textarea('description_en', Input::old('description_en'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="total_code_count">@lang('common.total_code_count') :</label>
            {{ Form::number('total_code_count', Input::old('total_code_count'), array('class' => 'form-control')) }}
        </div>
        <div class="form-group">
            <label for="code">@lang('common.code') :</label>
            {{ Form::text('code', Input::old('code'), array('class' => 'form-control')) }}
        </div>
    
        <div class="form-group" style="padding-top: 1rem;">
            @if (count($subscriptions) > 0)
                <h3 class="text-center">@lang('common.choose_subscriptions_for_promo') :</h3>
                <ul id="subscriptions" style="margin: 3rem;">
                    @foreach($subscriptions as $subscription)
                        <li class="form-control" style="margin: 3px;" data-active="false" value="{{ $subscription->id }}">
                            {{ $subscription->name }} 
                            - {{ $subscription->duration }} @lang('common.months_count')
                            - <strike>{{ $subscription->old_price }} zł</strike>
                            {{ $subscription->new_price }} zł
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="text-center" style="padding-top: 1rem;">
            <input type="submit" value="@lang('common.create')" class="btn btn-primary">
        </div>

    {{ Form::close() }}

</div>
@endsection