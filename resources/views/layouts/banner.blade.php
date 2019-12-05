<div id="banner" class="text-center">
    <div id="banner-text">
        <p>
            @lang('common.terms_banner_description')
            <a target="_blank" href="{{ URL::to('private-policy') }}">
                @lang('common.private_policy_banner')
            </a>
            @lang('common.and_banner')
            <a target="_blank" href="{{ URL::to('regulations') }}">
                @lang('common.regulations')
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