<?php

declare(strict_types=1);

namespace App\Action\User;


use App\Action\GetUsersCollectionByIdsRequest;
use App\Repository\UserRepository;

class GetUsersCollectionByIdsAction
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(GetUsersCollectionByIdsRequest $request)
    {
        $usersIds = $request->getUsersIds();

        $users = array_map(
            function($id) {
                $user = $this->repository->getById($id);
                $user->avatar = $user->getAvatar();
                return $user;
            },
        $usersIds);

        return new GetUsersCollectionByIdsResponse($users);
    }
}
