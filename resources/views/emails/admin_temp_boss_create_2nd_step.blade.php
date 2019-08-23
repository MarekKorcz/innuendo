@component('mail::message')

# Ukończenie rejestracji w {{ config('app.name') }} !

Gratulacje {{$boss->name}} {{$boss->surname}}!<br><br>
Właśnie udało Ci się zarejestrować do {{ config('app.name') }}!<br><br>
Jesteśmy zachwyceni wizją naszej przyszłej owocnej współpracy oraz nie możemy się doczekać naszej pierwszej wizyty w Twoim biurze!

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

Dziękujemy, <br>
{{ config('app.name') }}
@endcomponent
