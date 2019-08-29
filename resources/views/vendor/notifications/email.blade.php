@component('mail::message')

# @lang('common.reset_password_to_your_account')

@lang('common.greetings') ,

@lang('common.reset_password_to_your_account_description')

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
@lang('common.password_reset')
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

