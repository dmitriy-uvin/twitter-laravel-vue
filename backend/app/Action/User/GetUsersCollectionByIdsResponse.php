<?php

declare(strict_types=1);

namespace App\Action\User;


use Illuminate\Support\Collection;

class GetUsersCollectionByIdsResponse
{
    private $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function getUsers()
    {
        return $this->users;
    }
}
