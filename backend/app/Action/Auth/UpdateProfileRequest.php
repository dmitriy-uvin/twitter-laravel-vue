<?php

declare(strict_types = 1);

namespace App\Action\Auth;

final class UpdateProfileRequest
{
    private $email;
    private $firstName;
    private $lastName;
    private $nickname;
    private $notifications;

    public function __construct(
        ?string $email,
        ?string $firstName,
        ?string $lastName,
        ?string $nickname,
        ?bool $notifications
    ) {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nickname = $nickname;
        $this->notifications = $notifications;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function getNotifications(): ?bool
    {
        return $this->notifications;
    }
}
