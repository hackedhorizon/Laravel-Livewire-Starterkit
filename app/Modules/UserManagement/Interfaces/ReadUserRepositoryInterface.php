<?php

namespace App\Modules\UserManagement\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ReadUserRepositoryInterface
 *
 * Represents a repository for reading user data.
 */
interface ReadUserRepositoryInterface
{
    /**
     * Get all users.
     *
     * @return Collection A collection of users.
     */
    public function getAllUsers(): Collection;

    /**
     * Get a user by their ID.
     *
     * @param  int  $id  User ID.
     * @return User|null The user instance if found, otherwise null.
     */
    public function findUserById($id): ?User;

    /**
     * Find a user by their username or email address.
     *
     * @param  string  $identifier  The username or email address to search for.
     * @return User|null The user instance if found, otherwise null.
     */
    public function findByUsernameOrEmail(string $identifier): ?User;
}
