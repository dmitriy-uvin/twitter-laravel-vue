<?php

declare(strict_types=1);

namespace App\Action\Comment;

use App\Entity\Like;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use Illuminate\Support\Facades\Auth;

class LikeCommentAction
{
    private $commentRepository;
    private $likeRepository;

    private const ADD_LIKE_STATUS = 'added';
    private const REMOVE_LIKE_STATUS = 'removed';

    public function __construct(CommentRepository $commentRepository, LikeRepository $likeRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
    }

    public function execute(LikeCommentRequest $request): LikeCommentResponse
    {
        $comment = $this->commentRepository->getById((int) $request->getCommentId());
        $userId = Auth::id();

        if($this->likeRepository->existsForCommentByUser($comment->getId(), $userId)) {
            $this->likeRepository->deleteForCommentByUser($comment->getId(), $userId);

            return new LikeCommentResponse(self::REMOVE_LIKE_STATUS);
        }

        $like = new Like();
        $like->forComment($userId, $comment->getId());

        $this->likeRepository->save($like);

        return new LikeCommentResponse(self::ADD_LIKE_STATUS);
    }
}
