@component('vendor.mail.html.message')

# @lang('common.completing_registration_in') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!

@lang('common.congratulations') {{$boss->name}} {{$boss->surname}}!<br><br>
@lang('common.you_just_registered_to') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!<br><br>

@lang('common.boss_create_with_promo_code_mail_text_first')<br><br>

@lang('common.boss_create_with_promo_code_mail_text_second')

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
