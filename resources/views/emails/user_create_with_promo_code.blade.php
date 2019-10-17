@component('mail::message')

# @lang('common.completing_registration_in') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!

@lang('common.congratulations') {{$user->name}} {{$user->surname}}!<br><br>
@lang('common.you_just_registered_to') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!<br><br>

@lang('common.user_create_with_promo_code_mail_text_first') ( {{$boss->name}} {{$boss->surname}} ),
@lang('common.user_create_with_promo_code_mail_text_second') @lang('navbar.my_account')
@lang('common.user_create_with_promo_code_mail_text_third') @lang('common.schedules')
@lang('common.user_create_with_promo_code_mail_text_fourth')

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
