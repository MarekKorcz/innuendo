@component('mail::message')
# Introduction

The body of your message.

Welcome, {{$boss->name}} {{$boss->surname}}

@component('mail::button', ['url' => $tempBossRegisterAddress])
Zarejestruj się
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
