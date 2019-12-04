@component('vendor.mail.html.message')

# @lang('common.registration_to') {{ config('app.name') }} {{ config('app.name_2nd_part') }}

@lang('common.greetings'), {{$employee->name}} {{$employee->surname}}

@lang('common.admin_temp_employee_create_mail_text')

@component('mail::button', ['url' => $tempEmployeeRegisterAddress])
@lang('common.register')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
