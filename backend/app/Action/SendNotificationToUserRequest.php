<?php

declare(strict_types=1);

namespace App\Action;

final class SendNotificationToUserRequest
{
    private $receiverId;
    private $likerId;
    private $tweetId;

    public function __construct(int $receiverId, int $likerId, int $tweetId)
    {
        $this->receiverId = $receiverId;
        $this->likerId = $likerId;
        $this->tweetId = $tweetId;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function getLikerId(): int
    {
        return $this->likerId;
    }

    public function getTweetId(): int
    {
        return $this->tweetId;
    }

}
