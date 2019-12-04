@component('vendor.mail.html.message')

# @lang('common.booking_an_appointment_on_the_day') {{$appointment->day}} {{$appointment->month}} {{$appointment->year}}

@lang('common.greetings_booking') {{$appointment->day}} {{$appointment->month}} {{$appointment->year}} @lang('common.from') {{$appointment->start_time}} @lang('common.to') {{$appointment->end_time}} @lang('common.in') {{$appointment->property->name}} @lang('common.was_successful') !

@lang('common.login_to_your_account_and_visit') @lang('common.my_account') > @lang('common.appointments') @lang('common.for_additional_information').

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
