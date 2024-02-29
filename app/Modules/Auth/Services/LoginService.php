<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Interfaces\LoginServiceInterface;
use Illuminate\Support\Facades\Auth;

class LoginService implements LoginServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function attemptLogin(string $identifier, string $password, bool $remember): bool
    {
        $credentials = [];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $identifier,
                'password' => $password,
            ];
        } else {
            $credentials = [
                'username' => $identifier,
                'password' => $password,
            ];
        }

        if (Auth::attempt($credentials, $remember)) {
            $this->onSuccessfulLogin();

            return true;
        }

        $this->onFailedLogin();

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function onSuccessfulLogin(): void
    {
        session()->regenerate();
        session()->flash('message_success', __('auth.success'));
    }

    /**
     * {@inheritdoc}
     */
    public function onFailedLogin(): void
    {
    }
}
