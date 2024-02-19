<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function attemptLogin(string $identifier, string $password): bool
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

        if (Auth::attempt($credentials)) {
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
        session()->flash('message', 'Successful login!');
    }

    /**
     * {@inheritdoc}
     */
    public function onFailedLogin(): void
    {

    }
}
