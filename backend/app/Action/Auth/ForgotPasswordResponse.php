<?php

declare(strict_types=1);

namespace App\Action\Auth;


class ForgotPasswordResponse
{
    private $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
