<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\Comment;
use App\Entity\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LikedCommentEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $receiver;
    private $liker;
    private $comment;

    public function __construct(User $receiver, User $liker, Comment $comment)
    {
        $this->receiver = $receiver;
        $this->liker = $liker;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.likedComment')
            ->subject('Your comment was liked!')
            ->with([
                'receiver' => $this->receiver,
                'liker' => $this->liker,
                'comment' => $this->comment
            ]);
    }
}
