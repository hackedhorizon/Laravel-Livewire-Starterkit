<?php

namespace App\Modules\Auth\Interfaces;

interface AuthServiceInterface
{
    /**
     * Attempt to authenticate the user.
     *
     * @param  string  $identifier  The username or email of the user.
     * @param  string  $password  The user's password.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function attemptLogin(string $identifier, string $password): bool;

    /**
     * Actions to perform on a successful login.
     *
     * - Regenerate session ID to avoid session fixation.
     * - Flash success message.
     */
    public function onSuccessfulLogin(): void;

    /**
     * Actions to perform on a failed login attempt.
     */
    public function onFailedLogin(): void;
}
