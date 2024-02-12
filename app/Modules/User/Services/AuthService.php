<?php

namespace App\Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        $this->onFailedLogin($identifier);

        return false;
    }

    /**
     * Actions to perform on a successful login.
     *
     * - Regenerate session ID to avoid session fixation.
     * - Flash success message.
     */
    private function onSuccessfulLogin(): void
    {
        session()->regenerate();
        session()->flash('message', 'Successful login!');
    }

    /**
     * Actions to perform on a failed login attempt.
     *
     * - Log the login attempt.
     */
    private function onFailedLogin($identifier): void
    {
        // Log the failed login attempt
        Log::warning('Failed login attempt', [
            'identifier' => $identifier,
            'ip_address' => request()->ip(),
            'timestamp' => now(),
        ]);
    }
}
