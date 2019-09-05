@component('mail::message')

# @lang('common.subscription_purchased') @lang('common.in') {{ config('app.name') }}

@lang('common.greetings'), {{$boss->name}} {{$boss->surname}}!<br><br>

@lang('common.thanks_for_subscription_purchase_header') {{$subscription->name}} @lang('common.in') {{ config('app.name') }}!

@lang('common.thanks_for_subscription_purchase_description')<br><br>
@lang('common.thanks_for_subscription_purchase_description_2')<br></br>
@lang('common.thanks_for_subscription_purchase_description_3')<br>

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }}
@endcomponent
