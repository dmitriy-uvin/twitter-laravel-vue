<?php

declare(strict_types=1);

namespace App\Action;


class GetUsersCollectionByIdsRequest
{
    private $usersCollectionIds;
    private $page;
    private $sort;
    private $direction;

    public function __construct(
        array $usersIds,
        ?int $page,
        ?string $sort,
        ?string $direction
    ) {
        $this->usersCollectionIds = $usersIds;
        $this->page = $page;
        $this->sort = $sort;
        $this->direction = $direction;
    }

    public function getUsersIds(): array
    {
        return $this->usersCollectionIds;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }
}
