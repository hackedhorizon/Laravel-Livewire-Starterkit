<?php

namespace App\Modules\User\Interfaces;

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers();

    /**
     * Get a user by their ID.
     *
     * @param int $id User ID.
     *
     * @return \App\Models\User|null
     */
    public function getUserById($id);

}
