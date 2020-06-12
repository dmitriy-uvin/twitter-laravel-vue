<?php

declare(strict_types=1);

namespace App\Action\Auth;


final class ForgotPasswordRequest
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
