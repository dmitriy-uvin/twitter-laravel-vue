@component("mail::message")

# Welcome to the {{ env("APP_NAME") }}!

Hello **{{ $user->first_name }} {{ $user->last_name }}**, thank you for registering on {{ env("APP_NAME") }}!

@component("mail::button", ['url' => env("FRONT_URL")])
    Visit {{ env("APP_NAME") }}!
@endcomponent

@endcomponent
