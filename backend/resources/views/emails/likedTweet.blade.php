@component("mail::message")

# Your tweet was liked!

Hello **{{ $receiver->first_name }} {{ $receiver->last_name }}**, we are glad to tell you that your tweet was liked by
<u>{{ $liker->first_name }} {{ $liker->last_name }}</u>

Your tweet:
@component('mail::panel')
    {{ $tweet->text }}
@endcomponent

@if ($tweet->image_url)
<img src="{{ $tweet->image_url }}" alt="Tweet image">
@endif

@component("mail::button", ['url' => env("FRONT_URL")."/tweets/".$tweet->id, 'color' => 'success'])
    Go to tweet
@endcomponent

@endcomponent
