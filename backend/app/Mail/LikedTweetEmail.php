<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\Tweet;
use App\Entity\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LikedTweetEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $receiver;
    private $liker;
    private $tweet;

    public function __construct(User $receiver, User $liker, Tweet $tweet)
    {
        $this->receiver = $receiver;
        $this->liker = $liker;
        $this->tweet = $tweet;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.likedTweet')
            ->subject('Your tweet was liked!')
            ->with([
                'receiver' => $this->receiver,
                'liker' => $this->liker,
                'tweet' => $this->tweet
            ]);
    }
}
