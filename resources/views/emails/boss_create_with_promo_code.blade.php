@component('mail::message')

# Ukończenie rejestracji w {{ config('app.name') }} !

Gratulacje {{$boss->name}} {{$boss->surname}}!<br><br>
Właśnie udało Ci się zarejestrować do {{ config('app.name') }}!<br><br>
Jesteśmy zachwyceni wizją naszej przyszłej owocnej współpracy oraz nie możemy się doczekać naszej pierwszej wizyty w Twoim biurze!<br><br>
By to przybliżyć, <strong>zaloguj się na swoje konto</strong> i <strong>skontaktuj z nami</strong>!

@component('mail::button', ['url' => $loginUrl])
Zaloguj się
@endcomponent

Dziękujemy, <br>
{{ config('app.name') }}
@endcomponent
