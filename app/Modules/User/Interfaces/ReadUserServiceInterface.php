<?php

namespace App\Modules\User\Interfaces;

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
     * @return array An array of user data.
     */
    public function getUsers();

    /**
     * Get a user by their ID.
     *
     * @param int $id User ID.
     *
     * @return array|null User data or null if the user is not found.
     */
    public function getUserById($id);
}
