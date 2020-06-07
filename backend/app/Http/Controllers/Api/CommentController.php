<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Action\Comment\AddCommentAction;
use App\Action\Comment\AddCommentRequest;
use App\Action\Comment\DeleteCommentAction;
use App\Action\Comment\DeleteCommentRequest;
use App\Action\Comment\GetCommentByIdAction;
use App\Action\Comment\GetCommentCollectionAction;
use App\Action\Comment\GetCommentCollectionByTweetIdAction;
use App\Action\Comment\UpdateCommentAction;
use App\Action\Comment\UpdateCommentRequest;
use App\Action\Comment\UploadCommentImageAction;
use App\Action\Comment\UploadCommentImageRequest;
use App\Action\GetByIdRequest;
use App\Action\GetCollectionRequest;
use App\Entity\Comment;
use App\Http\Controllers\ApiController;
use App\Http\Presenter\CommentAsArrayPresenter;
use App\Http\Request\Api\Comment\AddCommentHttpRequest;
use App\Http\Request\Api\Comment\UpdateCommentHttpRequest;
use App\Http\Request\Api\Comment\UploadCommentImageHttpRequest;
use App\Http\Response\ApiResponse;
use App\Http\Request\Api\CollectionHttpRequest;
use App\Action\Comment\GetCommentCollectionByTweetIdRequest;

final class CommentController extends ApiController
{
    private $getCommentCollectionAction;
    private $presenter;
    private $getCommentByIdAction;
    private $addCommentAction;
    private $getCommentCollectionByTweetIdAction;
    private $deleteCommentAction;
    private $updateCommentAction;
    private $uploadCommentImageAction;

    public function __construct(
        GetCommentCollectionAction $getCommentCollectionAction,
        CommentAsArrayPresenter $presenter,
        GetCommentByIdAction $commentByIdAction,
        AddCommentAction $addCommentAction,
        GetCommentCollectionByTweetIdAction $getCommentCollectionByTweetIdAction,
        DeleteCommentAction $deleteCommentAction,
        UpdateCommentAction $updateCommentAction,
        UploadCommentImageAction $uploadCommentImageAction
    ) {
        $this->getCommentCollectionAction = $getCommentCollectionAction;
        $this->presenter = $presenter;
        $this->getCommentByIdAction = $commentByIdAction;
        $this->addCommentAction = $addCommentAction;
        $this->getCommentCollectionByTweetIdAction = $getCommentCollectionByTweetIdAction;
        $this->deleteCommentAction = $deleteCommentAction;
        $this->updateCommentAction = $updateCommentAction;
        $this->uploadCommentImageAction = $uploadCommentImageAction;
    }

    public function getCommentCollection(CollectionHttpRequest $request): ApiResponse
    {
        $response = $this->getCommentCollectionAction->execute(
            new GetCollectionRequest(
                (int)$request->query('page'),
                $request->query('sort'),
                $request->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $this->presenter);
    }

    public function getCommentById(string $id): ApiResponse
    {
        $comment = $this->getCommentByIdAction->execute(new GetByIdRequest((int)$id))->getComment();

        return $this->createSuccessResponse($this->presenter->present($comment));
    }

    public function newComment(AddCommentHttpRequest $request): ApiResponse
    {
        $response = $this->addCommentAction->execute(
            new AddCommentRequest(
                $request->get('body'),
                (int)$request->get('tweet_id')
            )
        );

        return $this->created(
            $this->presenter->present(
                $response->getComment()
            )
        );
    }

    public function getCommentCollectionByTweetId(string $tweetId, CollectionHttpRequest $request): ApiResponse
    {
        $response = $this->getCommentCollectionByTweetIdAction->execute(
            new GetCommentCollectionByTweetIdRequest(
                (int)$tweetId,
                (int)$request->query('page'),
                $request->query('sort'),
                $request->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $this->presenter);
    }

    public function deleteCommentById(string $id): ApiResponse
    {
        $this->deleteCommentAction->execute(
            new DeleteCommentRequest(
                (int)$id
            )
        );

        return $this->createDeletedResponse();
    }

    public function updateCommentById(string $id, UpdateCommentHttpRequest $request): ApiResponse
    {
        $response = $this->updateCommentAction->execute(
            new UpdateCommentRequest(
                (int) $id,
                $request->get('body'))
        );

        return $this->createSuccessResponse(
            $this->presenter->present(
                $response->getComment()
            )
        );
    }

    public function uploadCommentImage(string $id, UploadCommentImageHttpRequest $request): ApiResponse
    {
        $response = $this->uploadCommentImageAction->execute(
            new UploadCommentImageRequest(
                (int)$id,
                $request->file('image')
            )
        );

        return $this->createSuccessResponse(
            $this->presenter->present(
                $response->getComment()
            )
        );
    }
}
