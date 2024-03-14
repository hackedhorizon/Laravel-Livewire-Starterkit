<?php

namespace App\Modules\Authentication\Interfaces;

/**
 * Interface LoginServiceInterface
 *
 * Represents an interface for user authentication.
 */
interface LoginServiceInterface
{
    /**
     * Attempt to authenticate the user.
     *
     * @param  string  $identifier  The username or email of the user.
     * @param  string  $password  The user's password.
     * @param  bool  $remember  Should we remember the user for a longer time.
     * @return bool True if authentication is successful, false otherwise.
     */
    public function attemptLogin(string $identifier, string $password, bool $remember): bool;

    /**
     * Actions to perform on a successful login.
     * - Regenerate session ID to avoid session fixation.
     * - Flash success message.
     */
    public function onSuccessfulLogin(): void;

    /**
     * Actions to perform on a failed login attempt.
     */
    public function onFailedLogin(): void;
}
