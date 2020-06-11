<?php

declare(strict_types=1);

namespace App\Action;

final class SendNotificationToUserRequest
{
    private $receiverId;
    private $likerId;
    private $likedEntityId;
    private $type;

    public function __construct(
        int $receiverId,
        int $likerId,
        int $likedEntityId,
        string $type
    ) {
        $this->receiverId = $receiverId;
        $this->likerId = $likerId;
        $this->likedEntityId = $likedEntityId;
        $this->type = $type;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function getLikerId(): int
    {
        return $this->likerId;
    }

    public function getLikedEntityId(): int
    {
        return $this->likedEntityId;
    }

    public function getType(): string
    {
        return $this->type;
    }

}
