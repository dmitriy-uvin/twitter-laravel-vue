<?php

declare(strict_types=1);

namespace App\Action\User;


use App\Action\GetUsersCollectionByIdsRequest;
use App\Action\PaginatedResponse;
use App\Repository\UserRepository;

class GetUsersCollectionByIdsAction
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GetUsersCollectionByIdsRequest $request): PaginatedResponse
    {
        $usersIds = $request->getUsersIds();

        return new PaginatedResponse(
            $this->repository->paginateByUsersIds(
                $usersIds,
                $request->getPage() ?: UserRepository::DEFAULT_PAGE,
                UserRepository::DEFAULT_PER_PAGE,
                $request->getSort() ?: UserRepository::DEFAULT_SORT,
                $request->getDirection() ?: UserRepository::DEFAULT_DIRECTION
            )
        );
    }
}
