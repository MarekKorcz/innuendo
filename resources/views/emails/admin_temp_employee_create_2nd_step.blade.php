@component('mail::message')

# @lang('common.completing_registration_in') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!

@lang('common.congratulations') {{$employee->name}} {{$employee->surname}}!<br><br>
@lang('common.you_just_registered_to') {{ config('app.name') }} {{ config('app.name_2nd_part') }}!<br>

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
