<?php

declare(strict_types=1);

namespace App\Http\Presenter;

use App\Entity\Comment;
use Illuminate\Support\Collection;

final class CommentAsArrayPresenter implements CollectionAsArrayPresenter
{
    private $userArrayPresenter;
    private $likeArrayPresenter;

    public function __construct(UserArrayPresenter $userArrayPresenter, LikeArrayPresenter $likeArrayPresenter)
    {
        $this->userArrayPresenter = $userArrayPresenter;
        $this->likeArrayPresenter = $likeArrayPresenter;
    }

    public function present(Comment $comment): array
    {
        return [
            'id' => $comment->getId(),
            'body' => $comment->getBody(),
            'image_url' => $comment->getImageUrl(),
            'author_id' => $comment->getAuthorId(),
            'tweet_id' => $comment->getTweetId(),
            'created_at' => $comment->getCreatedAt()->toDateTimeString(),
            'updated_at' => $comment->getUpdatedAt() ? $comment->getUpdatedAt()->toDateTimeString() : null,
            'author' => $this->userArrayPresenter->present($comment->author),
            'likes_count' => $comment->getLikesCount(),
            'likes' => $this->likeArrayPresenter->presentCollection($comment->likes),
        ];
    }

    public function presentCollection(Collection $collection): array
    {
        return $collection
            ->map(
                function (Comment $comment) {
                    return $this->present($comment);
                }
            )
            ->all();
    }
}
