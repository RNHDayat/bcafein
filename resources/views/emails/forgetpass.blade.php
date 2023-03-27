@component('mail::message')
Dear {{ $name }}

Please open this generated link to change your password {{ route('api.changePassword', ['token' => $token]) }}

@component("mail::button", ['url' => route('api.changePassword', ['token' => $token])])
    Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
