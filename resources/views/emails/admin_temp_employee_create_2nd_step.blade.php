@component('mail::message')

# @lang('common.completing_registration_in') {{ config('app.name') }} !

@lang('common.congratulations') {{$employee->name}} {{$employee->surname}}!<br><br>
@lang('common.you_just_registered_to') {{ config('app.name') }}!<br>

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you') , <br>
{{ config('app.name') }}
@endcomponent
