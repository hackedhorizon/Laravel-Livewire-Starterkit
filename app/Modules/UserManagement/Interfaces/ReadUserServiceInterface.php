<?php

namespace App\Modules\UserManagement\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ReadUserServiceInterface
 *
 * Represents a service for reading user data.
 */
interface ReadUserServiceInterface
{
    /**
     * Get all users.
     *
     * @return Collection A collection of users.
     */
    public function getUsers(): Collection;

    /**
     * Get a user by their ID.
     *
     * @param  int  $id  User ID.
     * @return User|null User object or null if the user is not found.
     */
    public function findUserById($id): ?User;

    /**
     * Find a user by their username or email address.
     *
     * @param  string  $identifier  The username or email address to search for.
     * @return User|null The user instance if found, otherwise null.
     */
    public function findUserByUsernameOrEmail(string $identifier): ?User;
}