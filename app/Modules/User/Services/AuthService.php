<?php

namespace App\Modules\User\Services;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Attempt to authenticate the user.
     *
     * @param  string  $identifier  The username or email of the user.
     * @param  string  $password  The user's password.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function attemptLogin(string $identifier, string $password): bool
    {
        // Attempt authentication with email
        if (Auth::attempt(['email' => $identifier, 'password' => $password])) {
            $this->onSuccessfulLogin();

            return true;
        }

        // Attempt authentication with username
        if (Auth::attempt(['username' => $identifier, 'password' => $password])) {
            $this->onSuccessfulLogin();

            return true;
        }

        // Authentication failed
        return false;
    }

    /**
     * Actions to perform on a successful login.
     *
     * - Regenerate session ID for security.
     * - Flash success message.
     */
    private function onSuccessfulLogin()
    {
        // Regenerate session ID for security
        session()->regenerate();

        // Flash success message
        session()->flash('message', 'Successful login!');
    }
}
