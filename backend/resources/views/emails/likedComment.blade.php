@component("mail::message")

# Your comment was liked!

Hello **{{ $receiver->first_name }} {{ $receiver->last_name }}**, we are glad to tell you that your comment was liked by
<u>{{ $liker->first_name }} {{ $liker->last_name }}</u>

Your comment:
@component('mail::panel')
    {{ $comment->body }}
@endcomponent

@if ($comment->image_url)
<img src="{{ $comment->image_url }}" alt="Comment image">
@endif

@component("mail::button", ['url' => env("FRONT_URL")."/tweets/".$comment->tweet->id, 'color' => 'success'])
    Go to tweet
@endcomponent

@endcomponent
