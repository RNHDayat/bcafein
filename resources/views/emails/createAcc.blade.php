@component('mail::message')
Dear {{ $username }}

Welcome to the PowerShare Mr./Mrs. {{ $fullname }}. PowerShare is a powerful knowledge sharing application specially made for <i>Indonesian National Association of Consultants</i> / Ikatan Nasional Konsultan Indonesia (INKINDO) Consortium. You can ask questions in the forum, and answer other questions as much as you know. The knowledge would be shared easier here. Just enjoy the ride and explore the knowledge you may interested.

along with this email, your account can be activated by opening <a href="{{ route('api.verifyEmail', ['tokenMail' => $token]) }}">this page</a>, and returning to the login page.

@component("mail::button", ['url' => route('api.verifyEmail', ['tokenMail' => $token])])
    Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
