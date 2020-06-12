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
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json([
            'status' => 'Password Reseted',
            'response' => $response,
        ], 200);
    }


    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json([
            'status' => 'Something was wrong',
            'response' => $response,
        ], 500);
    }

    public function broker()
    {
        return Password::broker();
    }

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();
    }

    protected function setUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

}
