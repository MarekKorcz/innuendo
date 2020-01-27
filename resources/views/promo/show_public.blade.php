@extends('layouts.app')
@section('content')

{!! Html::script('js/promo_show_public.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        
        <div class="text-center">
            <div style="margin-bottom: 18px;">
                <h2>
                    @if (Session('locale') == "en")
                        {{ $promo->title_en }}
                    @else
                        {{ $promo->title }}
                    @endif
                </h2>
            </div>
            <div style="margin-bottom: 18px;">
                <h4>
                    @if (Session('locale') == "en")
                        {{ $promo->description_en }}
                    @else
                        {{ $promo->description }}
                    @endif
                </h4>
                <p>@lang('common.promo_status'): 
                    <strong>
                        @if ($promo->is_active == 1)
                            @lang('common.is_active')
                        @else
                            @lang('common.not_active')
                        @endif
                    </strong>
                </p>
            </div>
            <div style="margin-bottom: 30px;">
                <h4>
                    Napisz do nas by dowiedzieć się więcej:
                </h4>
                <div style="font-size: 21px;">
                    <a href="{{ URL::to('/contact') }}" target="_blank">
                        @lang('footer.contact')
                    </a> 
                </div>
            </div>
            <div>
                <div>
                    <h4>
                        @lang('common.public_promo_copy_code_description'): </br>
                    </h4>
                </div>
                <div>
                    <a class="btn pallet-1-3 copy-button" style="color: white;">
                        @lang('common.click_to_copy')
                    </a>
                    <input id="code" type="text" value="{{ $code }}">
                </div>
                <div id="register" style="padding-top: 1rem; font-size: 24px; visibility: hidden;">
                    <a href="{{ URL::to('/register') }}" target="_blank">
                        @lang('common.register')
                    </a> 
                </div>
            </div>
        </div>
    </div> 
</div>
@endsection