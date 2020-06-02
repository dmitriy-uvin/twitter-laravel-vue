<?php

declare(strict_types=1);

namespace App\Action\Comment;

use App\Repository\CommentRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class DeleteCommentAction
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function execute(DeleteCommentRequest $request): void
    {
        try {
            $comment = $this->commentRepository->getById($request->getId());
        } catch (ModelNotFoundException $e) {
            throw new Exception();
        }

        if($comment->author_id !== Auth::id()) {
            throw new AuthorizationException();
        }

        $this->commentRepository->delete($comment);
    }

}
