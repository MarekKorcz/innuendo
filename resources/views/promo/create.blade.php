@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/codes.css') }}">
<script src="{{ asset('js/promo_create.js') }}" defer></script>

<div class="container jumbotron">

    <div class="text-center" style="padding-bottom: 1rem;">
        <h1>@lang('common.create_promo')</h1>
    </div>

    <form id="promo-code" action="{{ action([App\Http\Controllers\AdminController::class, 'promoStore']) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">@lang('common.title'):</label>
            <input type="text" name="title" value="{{ old('title') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="title">@lang('common.title_en'):</label>
            <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="description">@lang('common.description'):</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="form-group">
            <label for="description_en">@lang('common.description_en'):</label>
            <textarea name="description_en" class="form-control">{{ old('description_en') }}</textarea>
        </div>
        <div class="form-group">
            <label for="total_code_count">@lang('common.total_code_count'):</label>
            <input type="number" name="total_code_count" value="{{ old('total_code_count') }}" class="form-control">
        </div>
        <div class="form-group">
            <label for="code">@lang('common.code'):</label>
            <input type="text" name="code" value="{{ old('code') }}" class="form-control">
        </div>

        <div class="form-group" style="padding-top: 1rem;">
            @if (count($subscriptions) > 0)
                <h3 class="text-center">@lang('common.choose_subscriptions_for_promo'):</h3>
                <ul id="subscriptions" style="margin: 3rem;">
                    @foreach($subscriptions as $subscription)
                        <li class="form-control" style="margin: 3px;" data-active="false" value="{{ $subscription->id }}">
                            {!! $subscription->name !!}
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

    </form>


</div>
@endsection
