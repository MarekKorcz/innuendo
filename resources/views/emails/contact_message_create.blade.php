@component('vendor.mail.html.message')

Email:
{{$message->email}}

Tytuł:
{{$message->topic}}

Tekst:
{{$message->text}}

@component('mail::button', ['url' => $loginUrl])
@lang('common.login')
@endcomponent

@lang('common.thank_you'), <br>
{{ config('app.name') }} {{ config('app.name_2nd_part') }}
@endcomponent
