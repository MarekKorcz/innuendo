@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}
{!! Html::style('css/graphic_request.css') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        
        <div class="text-center" style="margin-bottom: 18px; padding: 1rem;">
            <h1>
                @lang('common.promo_code_header'):
                @if (Session('locale') == "en")
                    {{ $promoCode->promo->title_en }}
                @else
                    {{ $promoCode->promo->title }}
                @endif
            </h1>
        </div>
        <div>
            <p>@lang('common.code'): <strong>{{ $promoCode->code }}</strong></p>
            <p>@lang('common.is_active'): 
                <strong>
                    @if ($promoCode->isActive == 1)
                        @lang('common.yes')
                    @else
                        @lang('common.no')
                    @endif
                </strong>
            </p>
            @if ($promoCode->isActive == 1)
                <p>
                    @lang('common.boss'):
                    <strong>
                        @if ($promoCode->boss !== null)
                            <a href="{{ URL::to('/admin/boss/show/' . $promoCode->boss->id) }}">
                                {{ $promoCode->boss->name }} {{ $promoCode->boss->surname }}
                            </a>
                        @else
                            @lang('common.unknown_boss')
                        @endif
                    </strong>
                </p>
                <p>@lang('common.activation_date'): <strong>{{ $promoCode->activation_date }}</strong></p>
            @endif
        </div>
        
        @if (count($promoCode->subscriptions) > 0)
            <h2 class="text-center">@lang('common.subscriptions_list'):</h2>
            <div class="form-group">
                <ul id="subscriptions" style="padding: 9px;">
                    @foreach ($promoCode->subscriptions as $subscription)
                        <a href="{{ URL::to('/subscription/show/' . $subscription->id) }}">
                            <li class="form-control text-center" style="background-color: lightgreen; margin: 3px;">
                                {!! $subscription->name !!}
                                - {{ $subscription->duration }} @lang('common.months_count')
                                - <strike>{{ $subscription->old_price }} zł</strike>
                                {{ $subscription->new_price }} zł
                            </li>
                        </a>
                    @endforeach
                </ul>
            </div> 
        @endif
            
        @if ($promoCode->boss !== null)
            <div class="text-center" style="padding-top: 1rem;">
                <h2>@lang('common.messages'):</h2>
                <a class="btn btn-success" href="{{ URL::to('/admin/approve/messages/' . $promoCode->boss->id) }}">
                    @lang('common.show')
                </a>
            </div>
        @endif
    </div> 
</div>
@endsection