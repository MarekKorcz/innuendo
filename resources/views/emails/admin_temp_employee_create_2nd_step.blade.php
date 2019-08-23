@component('mail::message')

# Ukończenie rejestracji w {{ config('app.name') }} !

Gratulacje {{$employee->name}} {{$employee->surname}}!<br><br>
Właśnie udało Ci się zarejestrować do {{ config('app.name') }}!<br>

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

Dziękujemy, <br>
{{ config('app.name') }}
@endcomponent
