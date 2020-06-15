<?php

declare(strict_types=1);

namespace App\Action;


final class GetUsersCollectionByIdsRequest extends GetCollectionRequest
{
    private $usersCollectionIds;

    public function __construct(
        array $usersIds,
        ?int $page,
        ?string $sort,
        ?string $direction
    ) {
        parent::__construct($page, $sort, $direction);

        $this->usersCollectionIds = $usersIds;
    }

    public function getUsersIds(): array
    {
        return $this->usersCollectionIds;
    }
}
