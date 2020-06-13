<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Action\GetByIdRequest;
use App\Action\GetCollectionRequest;
use App\Action\GetUsersCollectionByIdsRequest;
use App\Action\SendNotificationToUserRequest;
use App\Action\User\GetUserByIdAction;
use App\Action\User\GetUserCollectionAction;
use App\Action\User\GetUsersCollectionByIdsAction;
use App\Action\User\SendNotificationToUserAction;
use App\Http\Controllers\ApiController;
use App\Http\Presenter\UserArrayPresenter;
use App\Http\Request\Api\CollectionHttpRequest;
use App\Http\Request\Api\SendNotificationToUserHttpRequest;
use App\Http\Response\ApiResponse;

final class UserController extends ApiController
{
    private $getUserCollectionAction;
    private $presenter;
    private $getUserByIdAction;
    private $sendNotificationToUserAction;
    private $getUsersCollectionByIdsAction;

    public function __construct(
        GetUserCollectionAction $getUserCollectionAction,
        UserArrayPresenter $presenter,
        GetUserByIdAction $getUserByIdAction,
        SendNotificationToUserAction $sendNotificationToUserAction,
        GetUsersCollectionByIdsAction $getUsersCollectionByIdsAction
    ) {
        $this->getUserCollectionAction = $getUserCollectionAction;
        $this->presenter = $presenter;
        $this->getUserByIdAction = $getUserByIdAction;
        $this->sendNotificationToUserAction = $sendNotificationToUserAction;
        $this->getUsersCollectionByIdsAction = $getUsersCollectionByIdsAction;
    }

    public function getUserCollection(CollectionHttpRequest $request): ApiResponse
    {
        $response = $this->getUserCollectionAction->execute(
            new GetCollectionRequest(
                (int)$request->query('page'),
                $request->query('sort'),
                $request->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $this->presenter);
    }

    public function getUserById(string $id): ApiResponse
    {
        $user = $this->getUserByIdAction->execute(new GetByIdRequest((int)$id))->getUser();

        return $this->createSuccessResponse($this->presenter->present($user));
    }

    public function sendNotificationToUser(SendNotificationToUserHttpRequest $request): ApiResponse
    {
        $response = $this->sendNotificationToUserAction->execute(
            new SendNotificationToUserRequest(
                (int)$request->receiver,
                (int)$request->liker,
                (int)$request->liked_entity_id,
                $request->type
            )
        );

        return $this->createSuccessResponse();
    }

    public function getUsersCollectionByIds(CollectionHttpRequest $request): ApiResponse
    {
        $response = $this->getUsersCollectionByIdsAction->execute(
            new GetUsersCollectionByIdsRequest(
                $request->get('usersIds'),
                (int)$request->query('page'),
                $request->query('sort'),
                $request->query('direction')
            )
        );

        return $this->createSuccessResponse($response->getUsers());
    }
}
