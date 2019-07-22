@component('mail::message')

# Rejestracja do {{ config('app.name') }}

Witaj, {{$boss->name}} {{$boss->surname}}

W celu zakończenia procesu rejestracji, kliknij w poniższy przycisk i wypełnij pozostałe dane oraz hasło.

@component('mail::button', ['url' => $tempBossRegisterAddress])
Zarejestruj się
@endcomponent

Dziękujemy, <br>
{{ config('app.name') }}
@endcomponent
