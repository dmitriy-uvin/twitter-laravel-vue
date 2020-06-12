<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Action\Auth\ForgotPasswordAction;
use App\Action\Auth\ForgotPasswordRequest;
use App\Http\Controllers\ApiController;
use App\Http\Request\Api\Auth\ForgotPasswordHttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

final class ForgotPasswordController extends ApiController
{
    protected $forgotPasswordAction;


    public function __construct(ForgotPasswordAction $forgotPasswordAction)
    {
        $this->forgotPasswordAction = $forgotPasswordAction;
    }

    public function sendResetLinkEmail(ForgotPasswordHttpRequest $request)
    {
        $this->validateEmail($request);
        $response = $this->forgotPasswordAction->execute(
            new ForgotPasswordRequest(
                $request->get('email')
            )
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->createSuccessResponse(['email' => $response])
            : $this->createErrorResponse('Failed while trying to send email!', 500);
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }
}
