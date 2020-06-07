<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Action\Comment\LikeCommentAction;
use App\Action\Comment\LikeCommentRequest;
use App\Action\Tweet\LikeTweetAction;
use App\Action\Tweet\LikeTweetRequest;
use App\Http\Controllers\ApiController;
use App\Http\Response\ApiResponse;

final class LikeController extends ApiController
{
    private $likeTweetAction;
    private $likeCommentAction;

    public function __construct(LikeTweetAction $likeTweetAction, LikeCommentAction $likeCommentAction)
    {
        $this->likeTweetAction   = $likeTweetAction;
        $this->likeCommentAction = $likeCommentAction;
    }

    public function likeOrDislikeTweet(string $id): ApiResponse
    {
        $response = $this->likeTweetAction->execute(new LikeTweetRequest((int)$id));

        return $this->createSuccessResponse(['status' => $response->getStatus()]);
    }

    public function likeOrDislikeComment(string $id): ApiResponse
    {
        $response = $this->likeCommentAction->execute(new LikeCommentRequest((int)$id));

        return $this->createSuccessResponse(['status' => $response->getStatus()]);
    }
}
