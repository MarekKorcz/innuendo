@component('mail::message')

# Rejestracja do {{ config('app.name') }}

Witaj, {{$employee->name}} {{$employee->surname}}

W celu zakończenia procesu rejestracji, kliknij w poniższy przycisk i wypełnij pozostałe dane oraz hasło.

@component('mail::button', ['url' => $tempEmployeeRegisterAddress])
@lang('common.register')
@endcomponent

Dziękujemy, <br>
{{ config('app.name') }}
@endcomponent
