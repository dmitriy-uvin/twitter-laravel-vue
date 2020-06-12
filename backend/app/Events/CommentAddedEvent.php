<?php

declare(strict_types=1);

namespace App\Events;

use App\Entity\Comment;
use App\Http\Presenter\CommentAsArrayPresenter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\App;

class CommentAddedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = App::make(CommentAsArrayPresenter::class)->present($comment);
    }

    public function broadcastAs(): string
    {
        return 'comment.added';
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('comments');
    }
}
