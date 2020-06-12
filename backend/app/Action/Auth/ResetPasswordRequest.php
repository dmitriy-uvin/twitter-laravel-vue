<?php

declare(strict_types=1);

namespace App\Action\Auth;


final class ResetPasswordRequest
{
    private $email;
    private $password;
    private $password_confirmation;
    private $token;

    public function __construct(
        string $email,
        string $password,
        string $password_confirmation,
        string $token
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->token = $token;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function getPassword():string
    {
        return $this->password;
    }
    public function getPasswordConfirmation():string
    {
        return $this->password_confirmation;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getCredentials(): array
    {
        return [
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'password_confirmation' => $this->getPasswordConfirmation(),
            'token' => $this->getToken()
        ];
    }
}
