@component('mail::message')

# @lang('common.removal_of_reservation_from') {{$appointment->day}} {{$appointment->month}} {{$appointment->year}}

@lang('common.greetings_booking') {{$appointment->day}} {{$appointment->month}} {{$appointment->year}} @lang('common.from') {{$appointment->start_time}} @lang('common.to') {{$appointment->end_time}} @lang('common.in') {{$appointment->property->name}} @lang('common.has_been_removed') !

@lang('common.book_other_appointment_info').

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }}
@endcomponent
