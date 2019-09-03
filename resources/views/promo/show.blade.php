@extends('layouts.app')
@section('content')

{!! Html::style('css/codes.css') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        
        <div class="text-center" style="margin-bottom: 18px;">
            <h2>
                @if (Session('locale') == "en")
                    {{ $promo->title_en }}
                @else
                    {{ $promo->title }}
                @endif
            </h2>
        </div>
        <div class="text-center" style="margin-bottom: 30px;">
            <h4>
                @if (Session('locale') == "en")
                    {{ $promo->description_en }}
                @else
                    {{ $promo->description }}
                @endif
            </h4>
        </div>
        <div>
            <p>@lang('common.available_code_count') : <strong>{{ $promo->available_code_count }}</strong></p>
            <p>@lang('common.used_code_count') : <strong>{{ $promo->used_code_count }}</strong></p>
            <p>@lang('common.total_code_count') : <strong>{{ $promo->total_code_count }}</strong></p>
            <p>@lang('common.is_active') : 
                <strong>
                    @if ($promo->isActive == 1)
                        @lang('common.yes')
                    @else
                        @lang('common.no')
                    @endif
                </strong>
            </p>
        </div>
        
        @if (count($promo->promoCodes) > 0)
            <h3 class="text-center">@lang('common.promo_codes_list') :</h3>
            <div class="form-group">
                <ul id="promo-codes" style="padding: 18px;">
                    @foreach ($promo->promoCodes as $key => $promoCode)
                        @if ($promoCode->isActive)
                            <a href="{{ URL::to('/admin/promo-code/show/' . $promoCode->id) }}">
                                <li class="form-control text-center" style="background-color: lightgreen; margin: 3px;">
                                    {{ $key + 1 }} . @lang('common.used_by') {{ $promoCode->boss->name }} {{ $promoCode->boss->surname }}
                                </li>
                            </a>
                        @else
                            <a href="{{ URL::to('/admin/promo-code/show/' . $promoCode->id) }}">
                                <li class="form-control text-center" style="margin: 3px;">
                                    {{ $key + 1 }} . @lang('common.unused')
                                </li>
                            </a>
                        @endif
                    @endforeach
                </ul>
            </div> 
        @endif
    </div> 
</div>
@endsection