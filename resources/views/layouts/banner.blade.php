<div id="banner" class="text-center">
    <div id="banner-text">
        <p>
            @lang('common.terms_banner_description') &nbsp;
            <a target="_blank" href="{{ URL::to('cookies-policy') }}">
                @lang('cookies.cookies_policy')
            </a>
            &nbsp; , &nbsp; 
            <a target="_blank" href="{{ URL::to('private-policy') }}">
                @lang('private_policy.private_policy')
            </a>
            &nbsp; @lang('common.terms_banner_description_2') &nbsp;
            <a target="_blank" href="{{ URL::to('rodo') }}">
                @lang('common.rodo_policy')
            </a>
            .
        </p>
    </div>
    <div class="banner-button">
        <a id="understand" class="btn btn-sm pallet-1-3" style="color: white;">
            @lang('common.understand')
        </a>
    </div>
</div>