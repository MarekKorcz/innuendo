@component('mail::message')

# Resetowanie hasła do Twojego konta

Witaj,

Otrzymujesz ten e-mail, ponieważ dostaliśmy prośbę o zresetowanie hasła do Twojego konta. 
Kliknij w poniższy przycisk aby tego dokonać.

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
Resetuj
@endcomponent
@endisset

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
    "Jeśli masz problem z kliknięciem w przycisk \":actionText\", skopiuj i wklej poniższy adres URL\n".
    'do swojej przeglądarki: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endisset

Dziękujemy,<br>
{{ config('app.name') }}
@endcomponent

