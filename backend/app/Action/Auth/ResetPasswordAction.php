<?php

declare(strict_types=1);

namespace App\Action\Auth;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class ResetPasswordAction
{
    public function execute(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $request->getCredentials(), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response;
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
        $user->password = $password;
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }
}
