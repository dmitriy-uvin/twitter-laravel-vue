<?php

declare(strict_types=1);

namespace App\Action\Auth;

use App\Http\Request\Api\Auth\ForgotPasswordHttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

final class ForgotPasswordAction
{
    public function execute(ForgotPasswordRequest $request)
    {
        $response = $this->broker()->sendResetLink(
            ['email' => $request->getEmail()]
        );

        return $response;
    }

    public function broker()
    {
        return Password::broker();
    }
}
