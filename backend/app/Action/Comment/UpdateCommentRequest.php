<?php

declare(strict_types=1);

namespace App\Action\Comment;

final class UpdateCommentRequest
{
    private $id;
    private $body;

    public function __construct(int $id, ?string $body)
    {
        $this->id = $id;
        $this->body = $body;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
