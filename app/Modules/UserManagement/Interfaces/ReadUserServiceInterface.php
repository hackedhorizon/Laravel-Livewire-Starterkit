<?php

namespace App\Modules\UserManagement\Interfaces;

use App\Models\User;

/**
 * Interface ReadUserServiceInterface
 *
 * Represents a service for reading user data.
 */
interface ReadUserServiceInterface
{
    /**
     * Find a user by their username or email address.
     *
     * @param  string  $identifier  The username or email address to search for.
     * @return User|null The user instance if found, otherwise null.
     */
    public function findUserByUsernameOrEmail(string $identifier): ?User;
}
