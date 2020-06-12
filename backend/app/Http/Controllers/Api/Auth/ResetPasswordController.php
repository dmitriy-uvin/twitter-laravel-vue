<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Action\Auth\ResetPasswordAction;
use App\Action\Auth\ResetPasswordRequest;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Request\Api\Auth\ResetPasswordHttpRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class ResetPasswordController extends ApiController
{
    private $resetPasswordAction;

    public function __construct(ResetPasswordAction $resetPasswordAction)
    {
        $this->resetPasswordAction = $resetPasswordAction;
    }

    public function reset(ResetPasswordHttpRequest $request)
    {
        $response = $this->resetPasswordAction->execute(
            new ResetPasswordRequest(
                $request->get('email'),
                $request->get('password'),
                $request->get('password_confirmation'),
                $request->get('token')
            )
        );

        return $response == Password::PASSWORD_RESET
            ? $this->createSuccessResponse([
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ])
            : $this->createErrorResponse('Failed resetting password', 500);
    }
}
